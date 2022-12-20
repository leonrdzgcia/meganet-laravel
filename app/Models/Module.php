<?php

namespace App\Models;

use App\Http\Traits\IncludeFieldsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory, IncludeFieldsTrait;

    protected $guarded = [];

    public function packages()
    {
        return $this->morphToMany(
            Package::class,
            'crud_package',
            'crud_packages'
        )->withTimestamps();
    }

    public function fields()
    {
        return $this->hasMany(FieldModule::class)->orderBy('position');
    }

    public function columnsDatatable()
    {
        return $this->hasMany(ColumnDatatableModule::class);
    }

    public function getfields($id = null)
    {
        $fields = [];
        foreach ($this->fields()->get() as $field) {
            $fields[$field->name] = $this->transformToFieldsModel($field);
        }

        if ($id) {
            $fields = $this->assignValue($fields, $id);
        }
        return $fields;
    }

    public function getGeneralEditedFields()
    {
        $fields = [];
        foreach ($this->fields()->get() as $field) {
            $fields[$field->name] = $this->transformToFieldsModel($field);
        }
        return $this->assignValue($fields, 1);
    }

    public function getfieldsRelation($request)
    {
        $fields = [];
        foreach ($this->fields()->get() as $field) {
            $fields[$field->name] = $this->transformToFieldsModel($field);
        }

        $parent_module = 'App\Models\\' . $request->parent_module;
        $id = $request->id;
        $relation = $request->relation;
        $model = $parent_module::find($id);
        $result = $model->$relation;

        if ($result) return $this->includeFields('App\Models\\' . $this->name, $fields, $result);
        return $fields;
    }

    public function getColumnsDatatable($isAll = false)
    {
        if ($isAll) {
            return $this->columnsDatatable()
                ->with('user_column_datatable_module')
                ->get();
        }
        return $this->columnsDatatable()
            ->whereDoesntHave('user_column_datatable_module', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->get();
    }


    public function transformToFieldsModel($field)
    {
        return [
            'field' => $field->name,
            'type' => $field->type,
            'label' => $field->label,
            'placeholder' => $field->placeholder,
            'partition' => $field->partition,
            'include' => (boolean)$field->include,
            'hint' => $field->hint,
            'search' => [
                'model' => $field->search ? (json_decode($field->search)->model ?? null) : null,
                'id' => $field->search ? (json_decode($field->search)->id ?? null) : null,
                'text' => $field->search ? (json_decode($field->search)->text ?? null) : null,
                'filter' => $field->search ? (json_decode($field->search)->filter ?? null) : null
            ],
            'options' => $field->search ? null : json_decode($field->options),
            'inputGroup' => $field->inputGroup,
            'inputGroupEnd' => $field->inputGroupEnd,
            'depend' => $field->depend,
            'inputs_depend' => json_decode($field->inputs_depend),
            'value' => $field->value ? json_decode($field->value) : null,
            'default_value' => isset($field->default_value) ? json_decode($field->default_value) : null,
            'disabled' => $field->disabled,
            'position' => $field->position,
            'rule' => $field->rule,
            'module_id' => $field->module_id,
            'step' => $field->step,
        ];
    }

    public function assignValue($fields, $id)
    {
        $model = $this->getNameModel();
        $result = $model::find($id);
        if ($result) return $this->includeFields($model, $fields, $result);
        return $fields;
    }

    public function getNameModel()
    {
        return $this->is_main ? 'App\Models\\' . $this->name : $this->main;
    }
}
