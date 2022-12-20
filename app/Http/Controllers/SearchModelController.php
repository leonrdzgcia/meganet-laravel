<?php

namespace App\Http\Controllers;

use App\Models\ClientBundleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchModelController extends Controller
{
    public function search(Request $request){
        if (count($request->all())){
            $model = $request->model;
            $text = $request->text;
            $id = $request->id;
            $idModel = $request->idModel ?? null;
            if (isset($request->filter)){
                foreach ($request->filter as $key => $filter){
                    if ($key == 0){
                        if(isset($filter['field_relation'])){
                            $model = $model::whereHas($filter['field_relation'], function ($query) use ($filter){
                                $query->where($filter['field'], $filter['value']);
                            });
                        } else {
                            $model = $model::where($filter['field'], $filter['value']);
                        }

                       continue;
                    }
                    if (isset($filter['or_where'])){
                        if ($filter['value'] == 'client_id' && $idModel) {
                            $filter['value'] = $idModel;
                        }
                        if ($filter['value'] == 'bundle_id' && $idModel) {
                            $client_bundle_service = ClientBundleService::whereHas('service_internet')
                                ->where('id', $idModel)
                                ->first();
                            if ($client_bundle_service){
                                $client_internet_service = $client_bundle_service->service_internet()->first();
                                $filter['value'] = $client_internet_service->id;
                            }
                        }
                        $model = $model->orWhere($filter['or_where'], $filter['value']);
                        continue;
                    }

                    $model = $model->where($filter['field'], $filter['value']);
                }
                return $model->get()->pluck($text, $id);
            }
            return $model::get()->pluck($text, $id);
        }
    }
//TODO optimizar la relacion en el filtro del componente
    public function searchWithoutId(Request $request, $id){
        if (count($request->all())){
            $model = $request->model;
            $text = $request->text;
            $keyId = $request->id;
            if (isset($request->filter)){
                foreach ($request->filter as $key => $filter){
                    if ($key == 0){
                        if(isset($filter['field_relation'])){
                            $model = $model::whereHas($filter['field_relation'], function ($query) use ($filter){
                                $query->where($filter['field'], $filter['value']);
                            });
                        } else {
                            $model = $model::where($filter['field'], $filter['value']);
                        }
                        continue;
                    }
                    $model = $model->where($filter['field'], $filter['value']);
                }
                return $model->get()->pluck($text, $keyId);
            }
            return $model::where('id', '!=', $id)
                ->get()
                ->pluck($text, $keyId);
        }
    }
}
