<?php

namespace App\Http\Traits;

trait MultipleRelationTrait {
    public function saveRelationMultipleIfExist($model, $eloquent_model, $request, $action = 'attach')
    {
        if (defined($model . '::MULTIPLE_RELATIONS')){
            foreach ($model::MULTIPLE_RELATIONS as $key => $val){
                if (isset($request->$key) && count($request->$key)) $eloquent_model->$val()->$action($request->$key);
            }
        }
    }
}
