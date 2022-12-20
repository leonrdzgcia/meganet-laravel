<?php

namespace App\Models;

use App\Http\Controllers\FileController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\Utils\UtilController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Crm extends Model
{
    use HasFactory;
    protected $guarded = [];

    const MULTIPLE_RELATIONS = [
        'types_of_billing_id' => 'billings'
    ];

    const SINGLE_RELATIONS = [
        'CrmMainInformation' => [
            'relation_name' => 'crm_main_information',
            'relation_field' => 'crm_id',
            'local_relation' => 'id'
        ],
        'CrmLeadInformation' => [
            'relation_name' => 'crm_lead_information',
            'relation_field' => 'crm_id',
            'local_relation' => 'id'
        ]
    ];

    public function crm_main_information()
    {
        return $this->hasOne(CrmMainInformation::class);
    }

    public function crm_lead_information()
    {
        return $this->hasOne(CrmLeadInformation::class);
    }

    public function log_activities(){
        return $this->morphMany(LogActivity::class, 'logable');
    }

    public function billings(){
        return $this->morphToMany(
            TypeBilling::class,
            'plan_billing',
            'plan_type_billings'
        )->withTimestamps();
    }

    public function documents(){
        return $this->hasMany(DocumentCrm::class);
    }

    public function createMainInformation($request){
        $key = collect(config('module.crm.constants.CrmMainInformation.FIELDS'))->keys()->toArray();
        $input = $request->all();
        $util = new HelperController();
        $input["user"] = $util->getGenerateUser();
        $this->crm_main_information()->create(\Illuminate\Support\Arr::only($input, $key));
        return $this;
    }

    public function createLeadInformation($request){
        $key = collect(config('module.crm.constants.CrmLeadInformation.FIELDS'))->keys()->toArray();
        $this->crm_lead_information()->create(\Illuminate\Support\Arr::only($request->all(), $key));
        return $this;
    }

    public function createDocument($request){
        $document = $this->createCrmDocument($request->except('file'));
        return $this->uploadAndSaveDocumentUploaded($request->file, $document, $this->id);
    }

    public function createCrmDocument($input)
    {
        if (isset($input['show'])) $input['show'] = ($input['show'] == 'true');
        $input = $this->addCreatorId($input);
        return $this->documents()->create($input);
    }

    public function addCreatorId($input){
        $input['added_by_id'] = Auth::user()->id;
        return $input;
    }

    public function uploadAndSaveDocumentUploaded($file, $document, $idCrm)
    {
        $file_process = new FileController;
        $module_path = 'crm/'. $idCrm .'/document';
        $properties = $file_process->processSingleFileAndReturnProperties($file, $module_path, $document->id);

        $document->file()->create($properties);
        $file->storeAs('/public/'. $module_path .'/'. $document->id, $properties['name']);

        return true;
    }

    public function savePassword($field, $val){
        $this->crm_main_information()->update([
            $field => $val
        ]);
    }


    public function scopeFilters($query, $columns, $search = null)
    {
        if (isset($search)){
            return $query->where(function ($query) use ($search, $columns){
                foreach (collect($columns)->filter(function ($value){
                    return $value != 'action';
                })->toArray() as $value){
                    $query->orWhere($value,'like','%'.$search.'%');
                }
            });
        }
    }
}
