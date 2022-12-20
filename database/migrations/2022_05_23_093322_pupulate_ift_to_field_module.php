<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;
use App\Models\Ift;

class PupulateIftToFieldModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $bootstrap_multiselect = [1, 2];
        $select2 = [4, 5];
        $chosen_select = [21, 22];
        $toaster = [3];
        $datatables_packages = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
        $ckeditor = [23];
        $bootstrap_multiselect_toaster_datatables_packages = \Illuminate\Support\Arr::collapse([$bootstrap_multiselect, $toaster, $datatables_packages, $select2, $chosen_select, $ckeditor]);


        $module = Module::create(['name' => 'Ift']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.ift.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.ift.constants.', $module));

        $data = [
            ['name' => 'Basico Prepago 408345'],
            ['name' => 'Basico+ 4065505'],
            ['name' => 'Estudiante Prepago 408350'],
            ['name' => 'Estudiante+ 406506'],
            ['name' => 'Plus Prepago 408351'],
            ['name' => 'PLus+ 406508'],
            ['name' => 'Social Prepago 405322'],
            ['name' => 'Social+ 406504'],

        ];
        Ift::insert($data);

        $module = Module::where('name', 'Client')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.constants.', $module));

        $module = Module::where('name', 'ClientMainInformation')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.constants.', $module));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module = Module::where('name', 'Ift')->first();
        $module->packages()->detach();
        $module->columnsDatatable()->delete();
        $module->fields()->delete();
        $module->delete();
    }

    public function transformFieldsModel($dir, $module)
    {
        $fields = config($dir . $module->name . '.FIELDS');
        $id = $module->id;

        $values = [];
        if ($fields) {
            foreach ($fields as $k => $v) {
                $values[] = [
                    'name' => $k,
                    'type' => $v['type'] ?? 'depend-field',
                    'label' => $v['label'] ?? '',
                    'placeholder' => $v['placeholder'] ?? '',
                    'partition' => $v['partition'] ?? '',
                    'include' => isset($v['type']) && $v['type'] != 'depend-field' ? ($v['include'] ?? true) : false,
                    'hint' => $v['hint'] ?? '',
                    'search' => isset($v['search']) ? json_encode($v['search']) : null,
                    'options' => !isset($v['search']) ? json_encode($v['options'] ?? []) : null,
                    'inputGroup' => $v['inputGroup'] ?? '',
                    'inputGroupEnd' => $v['inputGroupEnd'] ?? '',
                    'depend' => $v['depend'] ?? null,
                    'inputs_depend' => $v['inputs_depend'] ?? null,
                    'value' => isset($v['value']) ? json_encode($v['value']) : null,
                    'default_value' => isset($v['default_value']) ? json_encode($v['default_value']) : null,
                    'disabled' => $v['disabled'] ?? false,
                    'position' => $v['position'] ?? 0,
                    'rule' => isset($v['rule']) ? json_encode($v['rule']) : null,
                    'module_id' => $id,
                    'step' => $v['step'] ?? null
                ];
            }
        }
        return $values;
    }

    public function transformColumnsDatatableModel($dir, $module)
    {
        $values = [];
        $columns = config($dir . $module->name . '.DATATABLE_FIELDS');
        if (count($columns)) {
            foreach ($columns as $k => $v) {
                $values[] = [
                    'module_id' => $module->id,
                    'name' => $k,
                    'label' => $v['name'],
                    'class' => $v['class'],
                ];
            }
        }
        return $values;
    }
}
