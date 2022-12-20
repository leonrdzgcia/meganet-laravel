<?php

namespace App\Http\Controllers\Module\Client;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\ClientAdditionalInformation;
use App\Models\ClientBundleService;
use App\Http\HelpersModule\module\client\ClientBundleServiceDatatableHelper;
use App\Models\ClientCustomService;
use App\Models\ClientInternetService;
use App\Models\ClientVozService;
use App\Models\Custom;
use App\Models\Internet;
use App\Models\Module;
use App\Models\Voise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Http\Traits\RouterConnection;

class ClientBundleServiceController extends Controller

{
    use RouterConnection;
    private $helper;

    public function __construct(ClientBundleServiceDatatableHelper $helper)
    {
        $model = 'ClientBundleService';
        $this->data['url'] = 'meganet.module' . $model;
        $this->data['module'] = 'ClientBundleService';
        $this->data['model'] = 'App\Models\\' . $model;
        $this->helper = $helper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->includeLibraryDinamic($this->data['model']);
        return view($this->data['url'] . '.add', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $idClient)
    {
        $input = $request->all();
        $arraValidation = $this->getInputValidation($input);
        $input = $request->validate($arraValidation);

        $bundleService = $this->createOrUpdateBundleService($input, $idClient);
        $this->createOrUpdateInternetService($bundleService->id, $input, $idClient);
        $this->createOrUpdateVozService($bundleService->id, $input, $idClient);
        $this->createOrUpdateCustomService($bundleService->id, $input, $idClient);
        return $bundleService;
    }



    public function createOrUpdateBundleService($input, $idClient, $id = null)
    {
        $bundle_service = collect($input)->filter(function ($val, $key) {
            return Str::contains($key, ['bundle_']);
        })->mapWithKeys(function ($item, $key) {
            if ($key != 'bundle_id') return [Str::after($key, 'bundle_') => $item];
            return [$key => $item];
        });
        $bundle_input = $bundle_service->toArray();
        if ($id) {
            $clientBundleService = ClientBundleService::find($id);
            $clientBundleService->update($bundle_input);
            return $clientBundleService;
        }
        $bundle_input["client_id"] = $idClient;
        return ClientBundleService::create($bundle_input);
    }

    public function createOrUpdateInternetService($bundleServiceId, $input, $idClient, $bundleService = null)
    {
        $relation = 'plan_internet';
        $plan = $this->filterPlan($input, $relation);
        if ($plan->count()) {
            $id = $this->getIdPlan($plan);
            $chunk_by = $this->getChunkBy($plan, $id);

            foreach ($plan->chunk($chunk_by) as $item) {
                $id_plan = $this->getIdPlan($item);

                $plan_service = $item->mapWithKeys(function ($item, $key) use ($relation) {
                    $key = Str::after($key, $relation . '_');
                    $key = Str::beforeLast($key, '_');
                    return [$key => $item];
                });

                  $plan_input = $plan_service->toArray();
                if ($bundleService) {
                    ClientInternetService::where('client_bundle_service_id', $bundleService->id)
                        ->where('internet_id', $id_plan)
                        ->first()
                        ->update($plan_input);
                } else {
                    $service = Internet::find($id_plan);
                    $plan_input["client_id"] = $idClient;
                    $plan_input["internet_id"] = $service->id;
                    $plan_input["client_bundle_service_id"] = $bundleServiceId;
                    $plan_input["description"] = $service->service_name;
                    $plan_input["amount"] = 1;
                    $plan_input["unity"] = 1;
                    $plan_input["price"] = $service->price;
                    $plan_input["pay_period"] = 'Periodo 1';
                    $plan_input["start_date"] = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
                    $plan_input["estado"] = 'Activado';


                    ClientInternetService::create($plan_input);
                }
            }
        }
    }

    public function createOrUpdateVozService($bundleServiceId, $input, $idClient, $bundleService = null)
    {
        // Crear Voz Service
        $relation = 'plan_voz';
        $plan = $this->filterPlan($input, $relation);

        if ($plan->count()) {
            $id = $this->getIdPlan($plan);
            $chunk_by = $this->getChunkBy($plan, $id);

            foreach ($plan->chunk($chunk_by) as $item) {
                $id_plan = $this->getIdPlan($item);

                $plan_service = $item->mapWithKeys(function ($item, $key) use ($relation) {
                    $key = Str::after($key, $relation . '_');
                    $key = Str::beforeLast($key, '_');
                    return [$key => $item];
                });

                $plan_input = $plan_service->toArray();
                if ($bundleService) {
                    $bundleService->service_voz()->where('voz_id', $id_plan)->update($plan_input);
                } else {
                    $service = Voise::find($id_plan);

                    $plan_input["client_id"] = $idClient;
                    $plan_input["voz_id"] = $service->id;
                    $plan_input["client_bundle_service_id"] = $bundleServiceId;
                    $plan_input["description"] = $service->service_name;
                    $plan_input["amount"] = 1;
                    $plan_input["unity"] = 1;
                    $plan_input["price"] = $service->price;
                    $plan_input["pay_period"] = 'Periodo 1';
                    $plan_input["start_date"] = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
                    $plan_input["estado"] = 'Activado';

                    ClientVozService::create($plan_input);
                }
            }
        }
    }

    public function createOrUpdateCustomService($bundleServiceId, $input, $idClient, $bundleService = null)
    {
        // Crear Custom Service
        $relation = 'plan_custom';
        $plan = $this->filterPlan($input, $relation);

        if ($plan->count()) {
            $id = $this->getIdPlan($plan);
            $chunk_by = $this->getChunkBy($plan, $id);

            foreach ($plan->chunk($chunk_by) as $item) {
                $id_plan = $this->getIdPlan($item);

                $plan_service = $item->mapWithKeys(function ($item, $key) use ($relation) {
                    $key = Str::after($key, $relation . '_');
                    $key = Str::beforeLast($key, '_');
                    return [$key => $item];
                });

                if($plan_service['user'] != '' || $plan_service['password'] != '' ){
                    $client = ClientAdditionalInformation::where('client_id',ClientBundleService::find($bundleServiceId)->client_id)->first();

                    $client->update([
                        'user_film' => $plan_service['user'],
                        'password_film' => $plan_service['password']
                    ]);
                }

                $plan_input = [];
                if (!$bundleService) {
                    $service = Custom::find($id_plan);

                    $plan_input["client_id"] = $idClient;
                    $plan_input["custom_id"] = $service->id;
                    $plan_input["client_bundle_service_id"] = $bundleServiceId;
                    $plan_input["description"] = $service->service_name;
                    $plan_input["amount"] = 1;
                    $plan_input["unity"] = 1;
                    $plan_input["price"] = $service->price;
                    $plan_input["pay_period"] = 'Periodo 1';
                    $plan_input["start_date"] = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
                    $plan_input["estado"] = 'Activado';

                    ClientCustomService::create($plan_input);
                }
            }
        }
    }

    public function filterPlan($input, $plan)
    {
        return collect($input)->filter(function ($val, $key) use ($plan) {
            return Str::contains($key, [$plan]);
        });
    }

    public function getIdPlan($plan)
    {
        return Str::afterLast($plan->keys()[0], '_');
    }

    public function getChunkBy($plan, $id)
    {
        return $plan->filter(function ($val, $key) use ($id) {
            return Str::endsWith($key, '_' . $id);
        })->count();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ClientBundleService $clientBundleService
     * @return \Illuminate\Http\Response
     */
    public function show(ClientBundleService $clientBundleService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ClientBundleService $clientBundleService
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientBundleService $clientBundleService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ClientBundleService $clientBundleService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $arraValidation = $this->getInputValidation($input);
        $input = $request->validate($arraValidation);
        $idClient = null;

        $bundleService = $this->createOrUpdateBundleService($input, $idClient, $id);
        $this->createOrUpdateInternetService($bundleService->id, $input, $idClient, $bundleService);
        $this->createOrUpdateVozService($bundleService->id, $input, $idClient, $bundleService);
        $this->createOrUpdateCustomService($bundleService->id, $input, $idClient, $bundleService);

        return $bundleService;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ClientBundleService $clientBundleService
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->data['model']::findOrFail($id)->delete();
        return redirect()->back()->with('message', $this->data['module'] . ' Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }

    public function getPlansById(Request $request, $bundleId)
    {
        $module = Module::where('name', 'ClientBundleService')->first();
        $fields = $module->getfields();
        $bundle = Bundle::find($bundleId)->load(['planes_internet', 'planes_voz', 'planes_custom']);

        $fields["bundle_id"]["value"] = $bundleId;
        // bundle_service_option
        $fields["bundle_description"]["value"] = $bundle->service_description;
        $fields["bundle_price"]["value"] = $bundle->price;

        $fields_planes_internet = collect($fields)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_internet_']);
        })->toArray();
        $fields_planes_voz = collect($fields)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_voz_']);
        })->toArray();
        $fields_planes_custom = collect($fields)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_custom_']);
        })->toArray();

        $fields = collect($fields)->filter(function ($value, $key) {
            return !Str::contains($key, ['plan_internet_']);
        });
        $fields = collect($fields)->filter(function ($value, $key) {
            return !Str::contains($key, ['plan_voz_']);
        });
        $fields = collect($fields)->filter(function ($value, $key) {
            return !Str::contains($key, ['plan_custom_']);
        });


        foreach ($bundle->planes_internet as $plan) {
            $keyed = collect($fields_planes_internet)->mapWithKeys(function ($item, $key) use ($plan) {
                return [$key . '_' . $plan->id => $item];
            })->map(function ($val) use ($plan) {
                $val["field"] = $val["field"] . '_' . $plan->id;
                $val["partition"] = $val["partition"] . '_' . $plan->id;
                if ($val["inputs_depend"]) {
                    $val["inputs_depend"] = $this->objectToArray($val["inputs_depend"]);
                    foreach ($val["inputs_depend"] as $k => $v) {
                        $newK = $k . '_' . $plan->id;
                        $v["field"] = $newK;
                        $val["inputs_depend"][$newK] = $v;
                        unset($val["inputs_depend"][$k]);
                    }
                }
                return $val;
            })->toArray();

            $fields = $fields->merge($keyed);
        }

        foreach ($bundle->planes_voz as $plan) {
            $keyed = collect($fields_planes_voz)->mapWithKeys(function ($item, $key) use ($plan) {
                return [$key . '_' . $plan->id => $item];
            })->map(function ($val) use ($plan) {
                $val["field"] = $val["field"] . '_' . $plan->id;
                $val["partition"] = $val["partition"] . '_' . $plan->id;
                return $val;
            })->toArray();

            $fields = $fields->merge($keyed);
        }

        foreach ($bundle->planes_custom as $plan) {
            $keyed = collect($fields_planes_custom)->mapWithKeys(function ($item, $key) use ($plan) {
                return [$key . '_' . $plan->id => $item];
            })->map(function ($val) use ($plan) {
                $field = str_replace('plan_custom_', '', $val["field"]);
                $val["value"] = $plan->$field;
                $val["field"] = $val["field"] . '_' . $plan->id;
                $val["partition"] = $val["partition"] . '_' . $plan->id;
                return $val;
            })->toArray();

            $fields = $fields->merge($keyed);
        }

        return [
            'fields' => $fields->toArray(),
            'planes_internet' => $bundle->planes_internet,
            'planes_voz' => $bundle->planes_voz,
            'planes_custom' => $bundle->planes_custom,
        ];
    }

    public function getEditedServiceBundleById($serviceBundleId)
    {
        $clientBundleService = ClientBundleService::find($serviceBundleId)->load(['service_internet', 'service_voz', 'service_custom']);
        $bundleId = $clientBundleService->bundle_id;

        $clientAdditional = ClientAdditionalInformation::where('client_id',$clientBundleService->client_id)->first();

        $module = Module::where('name', 'ClientBundleService')->first();
        $fields = $module->getfields();
        $bundle = Bundle::find($bundleId)->load(['planes_internet', 'planes_voz', 'planes_custom']);

        $fields["bundle_id"]["value"] = $bundleId;
        // bundle_service_option
        $fields["bundle_description"]["value"] = $bundle->service_description;
        $fields["bundle_price"]["value"] = $bundle->price;
        $fields["bundle_automatic_renewal"]["value"] = $clientBundleService->automatic_renewal;

        $fields_planes_internet = collect($fields)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_internet_']);
        })->toArray();
        $fields_planes_voz = collect($fields)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_voz_']);
        })->toArray();
        $fields_planes_custom = collect($fields)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_custom_']);
        })->toArray();

        $fields = collect($fields)->filter(function ($value, $key) {
            return !Str::contains($key, ['plan_internet_']);
        });
        $fields = collect($fields)->filter(function ($value, $key) {
            return !Str::contains($key, ['plan_voz_']);
        });
        $fields = collect($fields)->filter(function ($value, $key) {
            return !Str::contains($key, ['plan_custom_']);
        });

        foreach ($bundle->planes_internet as $plan) {
            $service_model = $clientBundleService->service_internet->where('internet_id', $plan->id)->first();
            $keyed = collect($fields_planes_internet)->mapWithKeys(function ($item, $key) use ($plan, $service_model) {
                $item["value"] = $this->assignVal($service_model, $item, 'plan_internet_');
                return [$key . '_' . $plan->id => $item];
            })->map(function ($val) use ($plan, $service_model) {
                $val["field"] = $val["field"] . '_' . $plan->id;
                $val["partition"] = $val["partition"] . '_' . $plan->id;
                if ($val["inputs_depend"]) {
                    $val["inputs_depend"] = $this->objectToArray($val["inputs_depend"]);
                    foreach ($val["inputs_depend"] as $k => $v) {
                        $v["value"] = $this->assignVal($service_model, $v, 'plan_internet_');
                        $newK = $k . '_' . $plan->id;
                        $v["field"] = $newK;
                        $val["inputs_depend"][$newK] = $v;
                        unset($val["inputs_depend"][$k]);
                    }
                }
                return $val;
            })->toArray();
            $fields = $fields->merge($keyed);
        }

        foreach ($bundle->planes_voz as $plan) {
            $service_model = $clientBundleService->service_voz->where('voz_id', $plan->id)->first();
            $keyed = collect($fields_planes_voz)->mapWithKeys(function ($item, $key) use ($plan, $service_model) {
                $item["value"] = $this->assignVal($service_model, $item, 'plan_voz_');
                return [$key . '_' . $plan->id => $item];
            })->map(function ($val) use ($plan) {
                $val["field"] = $val["field"] . '_' . $plan->id;
                $val["partition"] = $val["partition"] . '_' . $plan->id;
                return $val;
            })->toArray();

            $fields = $fields->merge($keyed);
        }

        foreach ($bundle->planes_custom as $plan) {
            $service_model = $clientBundleService->service_voz->where('custom_id', $plan->id)->first();
            $keyed = collect($fields_planes_custom)->mapWithKeys(function ($item, $key) use ($plan, $service_model) {
                $item["value"] = $this->assignVal($service_model, $item, 'plan_custom_');
                return [$key . '_' . $plan->id => $item];
            })->map(function ($val) use ($plan, $clientAdditional) {
                $field = str_replace('plan_custom_', '', $val["field"]);
                $val["value"] = $plan->$field;
                $val["field"] = $val["field"] . '_' . $plan->id;
                $val["partition"] = $val["partition"] . '_' . $plan->id;

                if ($plan->service_name == 'MovieNet') {
                    if ($field == 'user') {
                        $val["value"] = $clientAdditional->user_film;
                    }
                    if ($field == 'password') {
                        $val["value"] = $clientAdditional->password_film;
                    }
                }

                return $val;
            })->toArray();
            $fields = $fields->merge($keyed);
        }

        return [
            'fields' => $fields->toArray(),
            'planes_internet' => $bundle->planes_internet,
            'planes_voz' => $bundle->planes_voz,
            'planes_custom' => $bundle->planes_custom,
        ];
    }

    public function assignVal($model, $item, $relation)
    {
        $field = Str::after($item["field"], $relation);
        return $model->$field ?? null;
    }

    public function getInputValidation($input)
    {
        $data = [];

        $fields_planes_internet = collect($input)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_internet_']);
        })->map(function ($value, $key) use ($input) {
            $id = Str::afterLast($key, '_');
            if (Str::contains($key, ['router_id', 'ipv4_assignment', 'client_name', 'password'])) return 'required';
            if (Str::contains(
                $key,
                ['plan_internet_ipv4_' . $id]
            ) && $input['plan_internet_ipv4_assignment_' . $id] == 'IP Estatica') return 'required';
            if (Str::contains(
                $key,
                ['ipv4_pool']
            ) && $input['plan_internet_ipv4_assignment_' . $id] == 'Pool IP') return 'required';
            return '';
        });

        $fields_planes_voz = collect($input)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_voz_']);
        })->map(function ($value, $key) {
            if (Str::contains($key, ['router_id', 'ipv4_assignment', 'client_name', 'password'])) return 'required';
            return '';
        })->toArray();
        $fields_planes_custom = collect($input)->filter(function ($value, $key) {
            return Str::contains($key, ['plan_custom_']);
        })->map(function ($value, $key) {
            return '';
        })->toArray();
        $fields_service_bundle = collect($input)->filter(function ($value, $key) {
            return Str::contains($key, ['bundle_']);
        })->map(function ($value, $key) {
            return '';
        })->toArray();

        $data = collect($data)->merge($fields_planes_internet)->merge($fields_planes_voz)->merge($fields_planes_custom)->merge($fields_service_bundle);
        return $data->toArray();
    }

    public function objectToArray($object)
    {
        return collect($object)->map(function ($item, $key) {
            $res = [];
            foreach ($item as $k => $v) {
                $res[$k] = $v;
            }
            return $res;
        })->toArray();
    }
}
