<?php

namespace App\Http\Controllers;

use App\Http\Base\Encryption;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\Crm;
use App\Models\Location;
use App\Models\Module;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\RouterConnection;
use Illuminate\Support\Facades\DB;

class HelperController extends Controller
{
    use RouterConnection;

    public function getNextUserId()
    {
        return User::get()->count() ?? 0;
    }

    public function getFieldsByModule(Request $request)
    {
        $module = Module::where('name', $request->module)->first();
        return $module->getfields();
    }

    public function getFieldsByModuleRelation(Request $request)
    {
        $module = Module::where('name', $request->module)->first();
        return $module->getfieldsRelation($request);
    }

    public function getFieldsByModuleWithModuleRequested(Request $request)
    {
        $modelRequested = $request->modelRequest;
        $idModelRequest = $request->idModelRequest;
        $resultRequested = $modelRequested::find($idModelRequest);

        $idClient = $request->idClient;
        $userClient = Client::find($idClient)->user->user;

        $module = Module::where('name', $request->module)->first();
        $fields = $module->getfields();

        $relationTemp = $this->getRelationByModel($request->module);

        foreach ($fields as $key => $field) {
            if (isset($relationTemp[$key])) {
                $val = $resultRequested[$relationTemp[$key]];
                if ($key == $this->getRelationKeyByModel($request->module)) {
                    $val = (string)$val;
                }
                $fields[$key]['value'] = $val;
            }
        }
        if ($this->isClientInternetModule($request->module)) {
            $fields['client_name']['value'] = $userClient;
        }
        return $fields;
    }

    public function isClientInternetModule($module)
    {
        return $module == 'ClientInternetService';
    }

    public function getRelationByModel($model)
    {
        if ($model == 'ClientInternetService') {
            return [
                'internet_id' => 'id',
                'description' => 'title',
                'price' => 'price',
                'cost_activation' => 'cost_activation'
            ];
        }

        if ($model == 'ClientVozService') {
            return [
                'voz_id' => 'id',
                'description' => 'title',
                'price' => 'price'
            ];
        }

        if ($model == 'ClientCustomService') {
            return [
                'custom_id' => 'id',
                'description' => 'title',
                'price' => 'price'
            ];
        }
    }

    public function getRelationKeyByModel($model)
    {
        if ($model == 'ClientInternetService') {
            return 'internet_id';
        }

        if ($model == 'ClientVozService') {
            return 'voz_id';
        }
    }

    public function getFieldsEditedById(Request $request, $id)
    {
        $module = Module::where('name', $request->module)->first();
        return $module->getfields($id);
    }

    public function requestGeneralEditedFields(Request $request)
    {
        $module = Module::where('name', $request->module)->first();
        return $module->getGeneralEditedFields();
    }

    public function getColumnsByModule(Request $request)
    {
        $module = Module::where('name', $request->module)->first();
        return $module->getColumnsDatatable();
    }

    public function getAllColumnsByModule(Request $request)
    {
        $module = Module::where('name', $request->module)->first();
        return $module->getColumnsDatatable(true);
    }

    public function getRandomPassword()
    {
        return Encryption::randomPassword();
    }

    public function saveRandomPassword(Request $request)
    {
        $request->validate([
            'module' => 'required',
            'id' => 'required',
            'field' => 'required',
            'val' => 'required'
        ]);

        $module = Module::where('name', $request->module)->first();
        return $module->saveRandomPasswordByIdAndField($request->id, $request->field, $request->val);
    }

    public function getRandomUser()
    {
        return Encryption::randomUser();
    }

    public function getGenerateUser()
    {
        $user_number = 1;
        $preposition = 'Meganet100000';
        $lastUserId = ClientUser::count();
        if ($lastUserId) {
//            $number = $int = (int)filter_var($userName, FILTER_SANITIZE_NUMBER_INT);
            $user_number = $lastUserId + 1;
        }
        if (!ClientUser::where('user', $preposition . $user_number)->first()) {
            return $preposition . $user_number;
        } else {
            return $preposition . $user_number + 1;
        }
    }

    public function getData(Request $request, $module)
    {
        $modules = [
            'Partner' => 'getPartnerInfo',
            'Location' => 'getLocationInfo'
        ];

        if (isset($modules[$module])) {
            $function = $modules[$module];
            return $this->$function($request->id);
        }
        return null;
    }

    public function getPartnerInfo($id)
    {
        return Partner::where('id', $id)->with('internet', 'voz', 'router')->first()->toArray();
    }

    public function getLocationInfo($id)
    {
        return Location::where('id', $id)->with('router')->first()->toArray();
    }

    public function getUserAuthenticated()
    {
        return $this->userAutenticated()->id;
    }

    public function updateColumnsByUser(Request $request)
    {
        if (isset($request->module)) {
            $module = Module::where('name', $request->module)->first();
            if ($module) {
                $input = $request->except(['module']);
                foreach ($module->columnsDatatable()->get() as $item) {
                    $column = $item->name;
                    if (!($input[$column] ?? false)) {
                        if (
                        !($item->user_column_datatable_module()
                            ->where('user_id', $this->getUserAuthenticated())
                            ->first())
                        ) {
                            $item->user_column_datatable_module()->Create([
                                'user_id' => $this->getUserAuthenticated(),
                                'active' => false
                            ]);
                        }
                    } else {
                        $item->user_column_datatable_module()->where('user_id', $this->getUserAuthenticated())->delete();
                    }
                }
            }
        }

        return true;
    }


    public function getCrmClientIfExist(Request $request)
    {
        $input = $request->all();

        $column = [
            "name",
            "father_last_name",
            "mother_last_name",
            "email",
            "phone",
            "phone2",
            "email",
            "nif_pasaport",
            "street",
            "external_number",
            "internal_number",
        ];

        if ($this->requestNotEmpty($input, $column)) {
            $crm = Crm::with('crm_main_information')->whereHas('crm_main_information', function ($query) use ($column, $input) {
                if (isset($input)) {
                    $query->where(function ($query) use ($column, $input) {
                        foreach ($column as $value) {
                            if (isset($input['json'][$value])) {
                                if ($value == 'external_number' && $input['json'][$value] == 'Mz' ){
                                    $input['json'][$value] = null;
                                }

                               if ($value == 'internal_number' && $input['json'][$value] == 'Lt'){
                                   $input['json'][$value] = null;
                               }

                               if ($input['json'][$value] != '' || $input['json'][$value] != null)
                                $query->orWhere(DB::raw('LOWER('.$value.')'), '=', strtolower($input['json'][$value]));
                            }
                        }
                    });
                }
            })->get();

            $client = Client::with('client_main_information')->whereHas('client_main_information', function ($query) use ($column, $input) {
                if (isset($input)) {
                    $query->where(function ($query) use ($column, $input) {
                        foreach ($column as $value) {
                            if (isset($input['json'][$value])) {
                                if ($value == 'external_number' && $input['json'][$value] == 'Mz' ){
                                    $input['json'][$value] = null;
                                }

                                if ($value == 'internal_number' && $input['json'][$value] == 'Lt'){
                                    $input['json'][$value] = null;
                                }
                                $query->orWhere($value, '=', $input['json'][$value]);
                            }
                        }
                    });
                }
            })->get();

            $crm = collect($crm)->toArray();
            $client = collect($client)->toArray();

            return array_merge($crm, $client);
        }
        return null;
    }

    public function requestNotEmpty($input, $columns)
    {
        foreach ($columns as $column) {
            if ($input['json'][$column] != null) {
                return true;
            }
        }
        return false;
    }
}
