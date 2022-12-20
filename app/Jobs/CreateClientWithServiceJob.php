<?php

namespace App\Jobs;

use App\Models\MikrotikClientPpoe;
use App\Models\MikrotikTariffTargetTail;
use App\Models\NetworkIp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ClientInternetService;
use App\Models\Router;
use App\Models\Internet;
use App\Http\Traits\RouterConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MikrotikTariffMainTail;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\ClientAdditionalInformation;

class CreateClientWithServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RouterConnection;

    protected $clientInternetService;
    protected $model;
    protected $parentTailCommentPrefix;
    protected $parentTailNamePrefix;
    protected $sunTailNamePrefix;
    protected $internet;
    protected $client;
    protected $clientIp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ClientInternetService $clientInternetService, $model)
    {
        $this->clientInternetService = $clientInternetService;
        $this->model = $model;
        $router = Router::find($clientInternetService->router_id);
        $this->parentTailCommentPrefix = $router->mikrotikconfig->custom_config_comment_parent_router;
        $this->parentTailNamePrefix = $router->mikrotikconfig->custom_config_name_parent_router;
        $this->sunTailNamePrefix = $router->mikrotikconfig->custom_config_comment_sun_router;

        $this->internet = Internet::find($clientInternetService->internet_id);
        $this->client = $clientInternetService->client()->first();
        $this->clientIp = NetworkIp::find($clientInternetService->getIp())->ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $router = Router::find($this->clientInternetService->router_id);
        $device_ip = $router->ip_host;
        $tail = '';
        $son = '';

        if ($router->isMikrotik()) {
            $mikrotik = $router->mikrotik()->first();
            $connection = $this->initConnection($mikrotik, $device_ip);
            if ($this->isClientAdditionalInformationConectionTypeWifi($this->clientInternetService)){
                if ($this->isShapingTypeSimpleQueue($mikrotik)){
                    $parentTail = $this->getParentTailIfExistAndIfHasSpace();
                    if (!$parentTail) {
                        $tail = $this->getCountOfParentQueue();
                        $parentTail = $this->createParentQueue($mikrotik, $connection, $tail);
                        $son = 1;
                    } else {
                        $tail = $this->getCountOfParentQueue($parentTail);
                        $son = $this->getSonForTailParent($parentTail);
                    }
                    $this->addTargetToParentQueue($connection, $parentTail);
                    $this->createSunQueue($mikrotik, $connection, $tail, $parentTail, $son);
                }
            }

            $this->createClientPPoeIfNotExist($connection, $mikrotik, $tail, $son);
        }
        return false;
    }

    public
    function getParentTailIfExistAndIfHasSpace()
    {
        return MikrotikTariffMainTail::withCount('mikrotik_tariff_target_tail')
            ->leftJoin('internets', 'mikrotik_tariff_main_tails.tariff_id', '=', 'internets.id')
            ->where('mikrotik_tariff_main_tails.model', $this->model)
            ->where('mikrotik_tariff_main_tails.tariff_id', '=', $this->clientInternetService->internet_id)
            ->selectRaw('mikrotik_tariff_main_tails.*, internets.aggregation')
            ->get()
            ->filter(function ($val, $key) {
                return $val->mikrotik_tariff_target_tail_count < $val->aggregation;
            })->first();
    }

    public
    function getSonForTailParent($parentTail)
    {
        return $parentTail->mikrotik_tariff_target_tail_count + 1;
    }

    public
    function getCountOfParentQueue($parentTail = null)
    {
        if ($parentTail) {
            Log::info($parentTail);
            $json = json_decode($parentTail->json);
            $parentname = collect($json)->get('name');
            return Str::between($parentname, $this->parentTailNamePrefix . ' #', '-');
        }

        $count = MikrotikTariffMainTail::where('model', $this->model)
            ->where('tariff_id', '=', $this->clientInternetService->internet_id)
            ->count();
        return $count + 1;
    }

    public
    function _isDisable($disable)
    {
        $disable == 'Activado' || $disable == 'Nuevo' || $disable == 'Pendiente'
            ? $disable = 'yes'
            : $disable = 'no';
        return $disable;
    }

    public
    function _priority($priotity)
    {
        switch ($priotity) {
            case 'Baja':
                return '8';
                break;
            case 'Normal':
                return '5';
                break;
            case 'Alta':
                return '1';
                break;
        }
    }

    public
    function createParentQueue($mikrotik, $connection, $cola_id)
    {
        $array = ([
            'name' => $this->parentTailNamePrefix . ' #' . $cola_id . '-' . $this->internet->id,
            'comment' => $this->parentTailCommentPrefix . ' #' . $cola_id . '-' . $this->internet->service_name,
            'target' => null,
            'queue' => 'pcq-upload-default/pcq-download-default',
            'total-queue' => 'default-small',
            'parent' => 'none',
            'priority' => $this->_priority($this->internet->priority) . '/' . $this->_priority($this->internet->priority),
            'limit-at' => ($this->internet->upload_speed * 16) . '/' . ($this->internet->download_speed * 16),
            'burst-limit' => ($this->internet->upload_speed * 16 * 2) . '/' . ($this->internet->download_speed * 16 * 2),
            'max-limit' => ($this->internet->upload_speed * 16) . '/' . ($this->internet->download_speed * 16),
            'burst-time' => '800/800',
            'burst-threshold' => ($this->internet->upload_speed * 16) . '/' . ($this->internet->download_speed * 16)
        ]);

        if ($this->addItem($connection, '/queue simple ', $array)) {
            $datos = ([
                'mikrotik_id' => $mikrotik->id,
                'tariff_id' => $this->internet->id,
                'model' => $this->model,
                'json' => collect($array),
            ]);
            return MikrotikTariffMainTail::create($datos);
        }
        // TODO Crear Notificaciones error al crear cola padre
        return null;
    }

    public
    function createSunQueue($mikrotik, $connection, $cola_id, $parentTail, $idSon)
    {
            $array = [
                'name' => $this->sunTailNamePrefix . ' #' . $cola_id . $this->internet->id . '-' . $idSon,
                'comment' => $this->clientInternetService->client_name,
                'target' => $this->clientIp,
                'queue' => 'pcq-upload-default/pcq-download-default',
                'total-queue' => 'default-small',
                'parent' => $this->parentTailNamePrefix . ' #' . $cola_id . '-' . $this->internet->id,
                'priority' => $this->_priority($this->internet->priority) . '/' . $this->_priority($this->internet->priority),
                'limit-at' => ($this->internet->upload_speed) . '/' . ($this->internet->download_speed),
                'burst-limit' => ($this->internet->upload_speed) . '/' . ($this->internet->download_speed),
                'max-limit' => ($this->internet->upload_speed) . '/' . ($this->internet->download_speed),
                'burst-time' => '800/800',
                'burst-threshold' => ($this->internet->upload_speed) . '/' . ($this->internet->download_speed)
            ];

            $datos = ([
                'mikrotik_tariff_main_tail_id' => $parentTail->id,
                'mikrotik_id' => $mikrotik->id,
                'tariff_id' => $this->clientInternetService->id,
                'client_internet_service_id' => $this->clientInternetService->id,
                'model' => $this->model,
                'json' => collect($array),
            ]);

            $login = $this->client->clientGetUser();
            $haveSunSimpleQueue =  $this->getIdByComment($connection, '/queue/simple/',  $login);

            if (!$haveSunSimpleQueue){
                if ($this->addItem($connection, '/queue simple ', $array)) {
                $networkIp = NetworkIp::find($this->clientInternetService->ipv4)->first();

                $datosNetworkIp = [
                    'used' => true,
                    'used_by' => $this->clientInternetService->id,
                    'client_id' => $this->clientInternetService->client_id,
                ];

                if ($networkIp) {
                    $networkIp->update($datosNetworkIp);
                }

                $this->clientInternetService->update(['estado' => 'Activado']);
                return MikrotikTariffTargetTail::create($datos);
            }

            } else {
                $this->setvalueArrayById($connection,'/queue/simple/',
                    $this->getIdByComment($connection,'/queue/simple/', $login),
                  $array
            );

           $sunIp = $this->getIdByargument($connection,'/queue/simple/','.id',$haveSunSimpleQueue, 'target');

            $datos = ([
                'mikrotik_tariff_main_tail_id' => $parentTail->id,
                'mikrotik_id' => $mikrotik->id,
                'tariff_id' => $this->clientInternetService->id,
                'client_internet_service_id' => $this->clientInternetService->id,
                'model' => $this->model,
                'json' => collect($array),
            ]);

           $sunTail = MikrotikTariffTargetTail::where('id', $cola_id)->update($datos);
           if($sunTail) {
                $parernt = $sunTail->mikrotik_tariff_main_tail();
                return $parernt->update(['json' => str_replace($sunIp, $networkIp->ip, $parernt->json)]);
            }
        }
            // TODO Crear Notificaciones error al crear hijo
        return null;
    }

    public function createClientPPoeIfNotExist($connection, $mikrotik, $cola_id , $idSon )
    {
        $model = MikrotikClientPpoe::where('client_id', '=', $this->client->id)
            ->where('mikrotik_id', '=', $mikrotik->id)
            ->first();

        $login = $this->client->clientGetUser();
        $password = $this->client->clientGetPassword();
        $disabled = $this->client->clientGetStatus();
        $cola_id != '' ?  $comment = 'Meganet_' . $cola_id . $this->internet->id . '-' . $idSon : $comment =  'Meganet_' . $this->internet->id ;
        if (!$model) {
            if (!$this->getIdByName($connection, '/ppp/secret/',  $login)) {
                $this->addItem($connection, '/ppp secret ', [
                    'name' =>  $login,
                    'password' => $password,
                    'service' => 'any',
                    'profile' => 'default',
                    'remote-address' => $this->clientIp,
                    'disabled' => $this->_isDisable($disabled),
                    'comment' => $comment
                ]);
                $datos = [
                    'client_id' => $this->client->id,
                    'mikrotik_id' => $mikrotik->id,
                ];
                MikrotikClientPpoe::updateOrCreate($datos);
            }
        } else {

     $ipFromClientPpoeInMicrotik = $this->getIdByargument($connection, '/ppp/secret/' ,'name', $login, 'remote-address' );

     if ($ipFromClientPpoeInMicrotik != $this->clientIp ){
        $this->removeById($connection,'/ip/firewall/address-list/',
        $this->getIdByIp($connection, '/ip/firewall/address-list/', $ipFromClientPpoeInMicrotik));
        }

    $this->setvalueArrayById($connection,'/ppp/secret/',
                $this->getIdByName($connection,'/ppp/secret/', $login),
                [
                    'name' =>  $login,
                    'password' => $password,
                    'service' => 'any',
                    'profile' => 'default',
                    'remote-address' =>  $this->clientIp,
                    'disabled' => $this->_isDisable($disabled),
                ]
            );
        }
    }

    public
    function addTargetToParentQueue($connection, $parentTail)
    {
        if ($parentTail) {
            $json = json_decode($parentTail->json, true);
            $target = collect($json)->get('target');
            $parentname = collect($json)->get('name');
            $newTarget = $this->clientIp;
            $array = $this->updateJsonByTarget($json, $target, $newTarget);

            if ($this->setValueById($connection,
                '/queue/simple/',
                $this->getIdByName($connection, '/queue/simple/', $parentname),
                'target',
                $target . ',' . $newTarget)) {
                $parentTail->json = $array;
                $parentTail->update();
            }
        }
    }

    public
    function updateJsonByTarget($json, $target, $newTarget)
    {
        $arrayJson = collect($json)->toArray();
        Arr::set($arrayJson, 'target', $target . ',' . $newTarget);
        $newJson = json_encode($arrayJson);
        return $newJson;
    }

    public function isShapingTypeSimpleQueue($mikrotik){
      return  'Simple queue(Con árbol de cola)' == $mikrotik->shaping_type;
    }

    public function isClientAdditionalInformationConectionTypeWifi($clientInternetService){
     $clientAdditionalInformation = ClientAdditionalInformation::where('client_id', $clientInternetService->client_id)->first();
     return 'wifi' == $clientAdditionalInformation->connection_type;
    }
}
