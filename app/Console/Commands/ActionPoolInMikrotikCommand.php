<?php

namespace App\Console\Commands;

use App\Jobs\Client\RemoveServiceFromAddressList;
use App\Jobs\Mikrotik\MikrotikCreateAddressList;
use Illuminate\Console\Command;
use App\Models\MikrotikItemToExcecuteAction;
use App\Models\Router;
use App\Models\Network;
use App\Models\NetworkIp;
use App\Models\ClientInternetService;
use App\Http\Traits\RouterConnection;
use Illuminate\Support\Facades\Log;
use App\Models\Internet;
use App\Models\Client;

class ActionPoolInMikrotikCommand extends Command
{
    use RouterConnection;
    protected $secrcetIpOld;
    protected $networkIp;
    protected $clientInternetService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actionpoolinmikrotik:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina o actualiza Pools en los mikrotik dada las ubicaciones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    // TODO refatorizar despues
    public function handle()
    {
        foreach (MikrotikItemToExcecuteAction::all() as $v) {

            if ($v->model == 'Network') {
                $this->ActionOverPools($v);
            }
            if ($v->model == 'ClientInternetService') {
                $this->clientInternetService = ClientInternetService::find(
                    json_decode($v->value)->id
                );
                $this->ActionOverClientInternetService($v);
            }
            if ($v->model == 'MikrotikArray') {
                $this->ActionOverMikrotik($v);
            }
            if ($v->model == 'MikrotikConfig'){
                $this->ActionOverMikrotikConfig($v);
            }
            if ($v->model == 'ClientMainInformation'){
                $this->ActionOverMikrotikClientMainInformation($v);
            }

        }
    }

    public function ActionOverClientInternetService($v)
    {
        //TODO actualizar el user que esta en ClientMainInformation
        $client_name = json_decode($v->value)->client_name;
        $isDeleting = $v->action == 'delete';
        $isUpdate = $v->action == 'update';

        $router = Router::find(json_decode($v->value)->router_id);
        if ($router) {
            $device_ip = $router->ip_host;
            $device_login = $router->mikrotik->login_api;
            $device_password = $router->mikrotik->password_api;
            $device_port = $router->mikrotik->port_api;
            $connected = $this->connection(
                $device_ip,
                $device_login,
                $device_password,
                $device_port
            );

            if ($connected && $isDeleting) {
                if (
                    $this->getIdByName($connected, '/ppp/secret/', $client_name)
                ) {
                    $this->removeById(
                        $connected,
                        '/ppp/secret/',
                        $this->getIdByName(
                            $connected,
                            '/ppp/secret/',
                            $client_name
                        )
                    );
                }
            }

            if ($connected && $isUpdate) {
                $this->updateSecretIfExist($connected, $v);
                $this->updateClientAddressListIfExist($connected, $v);
                $this->updateQueueIfExist($connected, $v);
            }

            $v->delete();
        }
    }

    public function ActionOverPools($v)
    {
        foreach (
            Router::where(
                'location_id',
                json_decode($v->value)->location_id
            )->get()
            as $router
        ) {
            $pool = Network::find($v->id);
            $poolName = json_decode($v->value)->title;
            $poolComment = json_decode($v->value)->comment;
            $isDeleting = $v->action == 'delete';
            $isUpdate = $v->action == 'update';

            if ($router) {
                $device_ip = $router->ip_host;
                $mikrotik = $router->mikrotik();
                if ($mikrotik) {
                    $connected = $this->initConnection($mikrotik, $device_ip);

                    if ($connected && $isDeleting) {
                        if (
                            $this->getIdByName($connected, $v->place, $poolName)
                        ) {
                            $this->removeById(
                                $connected,
                                $v->place,
                                $this->getIdByName(
                                    $connected,
                                    $v->place,
                                    $poolName
                                )
                            );
                        }
                        $v->delete();
                    }

                    if ($connected && $isUpdate) {
                        if (
                            $this->getIdByComment(
                                $connected,
                                $v->place,
                                $poolComment
                            )
                        ) {
                            $this->setvalueArrayById(
                                $connected,
                                $v->place,
                                $this->getIdByComment(
                                    $connected,
                                    $v->place,
                                    $poolComment
                                ),
                                ['name' => $poolName]
                            );
                        }
                        $v->delete();
                    }
                }
            }
        }
    }

    public function ActionOverMikrotik($v)
    {
        $isDeleting = $v->action == 'delete';
        $isUpdate = $v->action == 'update';
        $router = Router::find($v->place); // En el campo obtenermos el id del router
        $device_ip = $router->ip_host;

         if ($router) {
            $mikrotik = $router->mikrotik()->first();
            if ($mikrotik) {
                $connected = $this->initConnection($mikrotik, $device_ip);
                if ($connected && $isUpdate) {
                $this->updateNatRulesMorososRedirct($connected, $router);
                $this->removeAll($connected, '/ip/proxy/access/');
                $this->addWebProxyAccessIpRedirect($connected, json_decode($v->value)->ip_redirect, $router);
                $this->addWebProxyAccessUrlRedirect($connected, json_decode($v->value)->url_redirect);
                $this->addWebProxyAccessIpsPermited($connected, json_decode($v->value)->ips_with_comma_permited);
                $v->delete();
            }
        }
    }
}

public function ActionOverMikrotikConfig($v){
    $isDeleting = $v->action == 'delete';
    $isUpdate = $v->action == 'update';
    $origin = json_decode($v->origin);
    $mikrotikConfig = json_decode($v->value);
    $router = Router::find(json_decode($v->value)->router_id);
    $device_ip = $router->ip_host;
    if ($router) {
        $mikrotik = $router->mikrotik()->first();
        if ($mikrotik) {
            $connected = $this->initConnection($mikrotik, $device_ip);
            if ($connected && $isUpdate) {
                $this->updateRulesInputApiAccept($connected, $router->port_api, $mikrotikConfig);
                $this->updatePpoeProfile($connected, $mikrotikConfig, $origin->mikrotik_config_server_pppoe_profile);
                $this->updateServerPppoe($connected, $mikrotikConfig, $origin->mikrotik_config_server_pppoe_name);

                $v->delete();
            }
        }
    }
}

    public function _isDisable($disable)
    {
        $disable == 'Activado' || $disable == 'Nuevo' || $disable == 'Pendiente'
            ? ($disable = 'no')
            : ($disable = 'yes');
        return $disable;
    }

    public function updateSecretIfExist($connected, $v)
    {
        $this->networkIp = NetworkIp::find(json_decode($v->value)->ipv4);
        if (
            $this->getIdByName(
                $connected,
                '/ppp/secret/',
                json_decode($v->value)->client_name
            )
        ) {
            $this->secrcetIpOld = $this->getIdByargument(
                $connected,
                '/ppp/secret/',
                'name',
                json_decode($v->value)->client_name,
                'remote-address'
            );
            $this->setvalueArrayById(
                $connected,
                '/ppp/secret/',
                $this->getIdByName(
                    $connected,
                    '/ppp/secret/',
                    json_decode($v->value)->client_name
                ),
                [
                    'name' => json_decode($v->value)->client_name,
                    'password' => json_decode($v->value)->password,
                    'service' => 'any',
                    'profile' => 'default',
                    'remote-address' => $this->networkIp->ip,
                    'disabled' => $this->_isDisable(
                        json_decode($v->value)->estado
                    ),
                ]
            );
            return true;
        }
        return null;
    }

    public function updateClientAddressListIfExist($connected, $v)
    {
        if (
            $this->getIdByIp(
                $connected,
                '/ip/firewall/address-list/',
                $this->secrcetIpOld
            )
        ) {
            $this->setvalueArrayById(
                $connected,
                '/ip/firewall/address-list/',
                $this->getIdByIp(
                    $connected,
                    '/ip/firewall/address-list/',
                    $this->secrcetIpOld
                ),
                [
                    'address' => $this->networkIp->ip,
                ]
            );
            return true;
        }
        return null;
    }

    public function updateQueueIfExist($connected, $v)
    {
        $mikrotik_tariff_target_tail = $this->clientInternetService
            ->mikrotik_tariff_target_tail()
            ->first();

        if ($mikrotik_tariff_target_tail) {
            $sunJson = $mikrotik_tariff_target_tail->first()->json;
            $sun = json_decode($sunJson);
            $sunQueueOldTarget = $sun->target;
            $SunParentName = $sun->parent;

            $sunNewJson = str_replace(
                $sunQueueOldTarget,
                $this->networkIp->ip,
                $sunJson
            );

            $sunQueueId = $this->getIdByComment(
                $connected,
                '/queue/simple/',
                $this->clientInternetService->client_name
            );

            if ($sunQueueId) {
                $this->setvalueArrayById(
                    $connected,
                    '/queue/simple/',
                    $sunQueueId,
                    $this->setNewQueuefromJson($sunNewJson)
                );

                $parentJson = $mikrotik_tariff_target_tail
                    ->mikrotik_tariff_main_tail()
                    ->first()->json;
                if ($parentJson) {
                    $parent = json_decode($parentJson);
                    $parerntTarget = $parent->target;

                    $parentNewJson = str_replace(
                        $sunQueueOldTarget,
                        $this->networkIp->ip,
                        $parentJson
                    );

                    $parerntQueueId = $this->getIdByName(
                        $connected,
                        '/queue/simple/',
                        $SunParentName
                    );
                    if ($parerntQueueId) {
                        $this->setvalueArrayById(
                            $connected,
                            '/queue/simple/',
                            $parerntQueueId,
                            $this->setNewQueuefromJson($parentNewJson)
                        );

                        $mikrotik_tariff_target_tail->update([
                            'json' => $this->setNewQueuefromJson($sunNewJson),
                        ]);
                        $parernt = $mikrotik_tariff_target_tail
                            ->mikrotik_tariff_main_tail()
                            ->first();
                        return $parernt->update([
                            'json' => $this->setNewQueuefromJson(
                                $parentNewJson
                            ),
                        ]);
                    }
                }
            }
        }
        return null;
    }

    public function _priority($priotity)
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

    public function setNewQueuefromJson($jsonString)
    {
        $json = json_decode($jsonString, true);
        return [
            'name' => $json['name'],
            'comment' => $json['comment'],
            'target' => $json['target'],
            'queue' => $json['queue'],
            'total-queue' => $json['total-queue'],
            'parent' => $json['parent'],
            'priority' => $json['priority'],
            'limit-at' => $json['limit-at'],
            'burst-limit' => $json['burst-limit'],
            'max-limit' => $json['max-limit'],
            'burst-time' => $json['burst-time'],
            'burst-threshold' => $json['burst-threshold'],
        ];
    }

    public function ActionOverMikrotikClientMainInformation($v){
        $client = Client::find(json_decode($v->value)->client_id);

        if (json_decode($v->origin)->estado == 'Bloqueado' && json_decode($v->value)->estado == 'Activo' ) {
            $clientService = $client->internet_service()->first();
            RemoveServiceFromAddressList::dispatchAfterResponse($clientService);
        }

        if (json_decode($v->origin)->estado == 'Activo' && json_decode($v->value)->estado == 'Bloqueado' ) {
            $clientService = $client->internet_service()->first();
            MikrotikCreateAddressList::dispatchAfterResponse($clientService);
        }

        $client->internet_service()->update([
            'client_name' => json_decode($v->value)->user,
            'password' => json_decode($v->value)->password,
        ]);

        $services = $client->internet_service()->get();
         $isUpdate = $v->action == 'update';
        if ($services->count()){
            foreach($services as $service){
                $router = Router::find($service->router_id);
                $device_ip = $router->ip_host;
                if ($router) {
                    $mikrotik = $router->mikrotik()->first();
                    if ($mikrotik) {
                        $connected = $this->initConnection($mikrotik, $device_ip);

                        if ($connected && $isUpdate) {
                            $dataUserbyUpdate = ([
                                'name' => json_decode($v->value)->user,
                                'password' => json_decode($v->value)->password,
                                'disabled' => 'no',
                                'service' => 'any',
                                'profile' => 'default',
                                'remote-address' => NetWorkIp::find($service->ipv4)->ip,
                            ]);

                                $this->setvalueArrayById($connected, '/ppp/secret/',
                                $this->getIdByName($connected,'/ppp/secret/', json_decode($v->origin)->user),
                                $dataUserbyUpdate
                            );
                        }
                    }
                }
            }
            $v->delete();
        }
    }

    public function isDisable($disable)
    {
        $disable == 'Activado' || $disable == 'Activo' || $disable == 'Nuevo' ? $disable = 'no' : $disable = 'yes';
        return $disable;
    }

}
