<?php

namespace App\Http\Traits;

use App\Http\Controllers\Module\Network\Ipv4CalculatorController;
use App\Models\Network;
use App\Models\NetworkIp;
use App\Models\Router;
use Illuminate\Support\Facades\Log;
use PEAR2\Net\RouterOS\Client;
use PEAR2\Net\RouterOS\Util;
use PEAR2\Net\RouterOS\Request;
use PEAR2\Net\RouterOS\Response;
use PEAR2\Net\RouterOS\Query;
use PEAR2\Net\RouterOS\SocketException;
use PhpParser\Node\Stmt\Return_;
use PHPUnit\Exception;

const IP_FIREWALL_NAT_WHIT_SLASH = '/ip/firewall/nat/'; //print  //add
const IP_FIREWALL_RULES_WHIT_SLASH = '/ip/firewall/filter/'; //print
const IP_FIREWALL_ADDRESS_LIST_WHIT_SLASH = '/ip/firewall/address-list/'; //print
const INTERFACE_PPPOE_CLIENT_WHIT_SLASH = '/interface/pppoe-client/'; //print
const QUEUE_SIMPLE_WHIT_SLASH = '/queue/simple/'; //print
const QUEUE_TYPE_WHIT_SLASH = '/queue/type/'; //print
const IP_ARP_WHIT_SLASH = '/ip/arp/'; // add IP Arp
const QUEUE_TYPE_WITH_SPACE = '/queue type '; // add Queue type
const QUEUE_SIMPLE_WHIT_SPACE = '/queue simple '; //add simple queue
const INTERCAFE_PPOE_CLIENT_WITH_SPACE = '/interface pppoe-client '; // add CLIENTE ppoe
const IP_FIREWALL_ADDRESS_LIST_WITH_SPACE = '/ip firewall address-list '; //add cliete in address-list
const IP_FIREWALL_FILTER_WITH_SPACE = '/ip firewall filter '; //add rules in firewall

trait RouterConnection
{
    public function initConnection($mikrotik, $device_ip)
    {
        $device_login = $mikrotik->login_api;
        $device_password = $mikrotik->password_api;
        $device_port = $mikrotik->port_api;
        return $this->connection(
            $device_ip,
            $device_login,
            $device_password,
            $device_port
        );
    }

    //        $device_ip = '192.168.1.50';
    //        $device_login = 'admin';
    //        $device_password = 'okk';
    //        $device_port = '8730';

    //  addicionar cualquier item a un lugar
    //    $this->addItem($connected,IP_ARP_WHIT_SLASH,(['address'=>'192.168.1.254','mac-address'=>'00:00:00:00:00:00','interface'=>'bridge1']));

    //  adicionar cliente en address list
    //    $this->addItem($connected,IP_FIREWALL_ADDRESS_LIST_WITH_SPACE,(['list'=>'SpLBL_blocked','address'=>'192.168.1.111','comment'=>'algo']));

    //  addicionar simple queue
    //    $this->addItem($connected,QUEUE_SIMPLE_WHIT_SPACE,([
    //           'name'=>'MEGANET_01-030122',
    //           'comment' =>'Meganet0301-Prueba-1111',
    //           'target'=>'192.168.1.3,192.168.1.4,192.168.1.5',
    //           'queue' =>'pcq-upload-default/pcq-download-default',
    //           'total-queue' =>'default',
    //           'parent'=>'SpLSTG_0-1',
    //           'priority'=>'5',
    //           'limit-at' =>'68750/275k',
    //           'burst-limit'=>'10M/40M',
    //           'max-limit' =>'5M/20M',
    //           'burst-time' =>'800/800',
    //           'burst-threshold' =>'5M/20M']));

    //    $this->addItem($connected,INTERCAFE_PPOE_CLIENT_WITH_SPACE,([
    //        'name'=>'ppoe-client-pepe',
    //        'user'=> 'pepe',
    //        'password'=>'Pass',
    //        'interface'=>'ether2',
    //        'service-name'=> '',
    //        'disabled'=>'no'
    //        ]));

    //     $this->addItem($connected,IP_FIREWALL_FILTER_WITH_SPACE,([
    //           'chain'=>'forward',
    //           'action'=>'drop',
    //           'src-address' =>null,
    //           'dst-address'=> null,
    //           'dst-address-type'=> null,
    //           'protocol'=> null,
    //           'src-port'=> null,
    //           'dst-port'=> null,
    //           'port'=> null,
    //           'in-interface'=> null,
    //           'out-interface'=> null,
    //           'src-address-list'=> 'SpLBL_blocked',
    //           'dst-address-list'=> '',
    //           'connection-state'=> '',
    //           'comment'=> 'Bloquea clientes morosos2'
    //          ]) );
    //
    //        $this->addItem($connected,IP_FIREWALL_FILTER_WITH_SPACE,([
    //            'chain'=>'forward',
    //            'action'=>'drop',
    //            'src-address' =>null,
    //            'dst-address'=> null,
    //            'dst-address-type'=> null,
    //            'protocol'=> null,
    //            'src-port'=> null,
    //            'dst-port'=> null,
    //            'port'=> null,
    //            'in-interface'=> null,
    //            'out-interface'=> null,
    //            'src-address-list'=> 'SpLBL_blocked',
    //            'dst-address-list'=> '',
    //            'connection-state'=> 'invalid',
    //            'comment'=> 'FORDWARE-Dropea invalidas2'
    //        ]) );
    //
    //        $this->addItem($connected,IP_FIREWALL_NAT_WHIT_SLASH,([
    //           'chain'=>'srcnat',
    //           'protocol'=>null,
    //           'action' =>'masquerade',
    //           'to-addresses'=>'',
    //           'to-ports'=>'',
    //           'src-address'=>'',
    //           'dst-address'=>'',
    //           'dst-port'=>'',
    //           'comment'=>''
    //        ]));
    //
    //        $this->addItem($connected,IP_FIREWALL_NAT_WHIT_SLASH,([
    //            'chain'=>'',
    //            'protocol'=>'',
    //            'action' =>'',
    //            'to-addresses'=>'',
    //            'to-ports'=>'',
    //            'src-address'=>'',
    //            'dst-address'=>'',
    //            'dst-port'=>'',
    //            'comment'=>''
    //        ]));

    //    $this->setvalueArrayById($connected,QUEUE_SIMPLE_WHIT_SLASH,
    //        $this->getIdByName($connected,QUEUE_SIMPLE_WHIT_SLASH,"SpLSTQ_3288-3962"),(
    //            ['comment' => 'hola',
    //             'target' => '192.168.1.211, 192.168.1.110'
    //            ])
    //        );

    //        $this->setvalueArrayById($connected,'/interface/pppoe-client/',
    //            $this->getIdByName($connected,
    //                '/interface/pppoe-client/',
    //                'ppoe-client-Meganet-Vladismer'),
    //            (['disabled' => 'yes']),
    //        );

    /**
     * Connect to mikrotik device
     * this function must be included in all other functions
     * @param $device_ip
     * @param $device_login
     * @param $device_password
     * @param $device_port
     * @return Client
     */
    protected function connection(
        $device_ip,
        $device_login,
        $device_password,
        $device_port
    ) {
        try {
            $client = new Client(
                $device_ip,
                $device_login,
                $device_password,
                $device_port
            );
        } catch (SocketException $e) {
            return null;
        }
        return $client;
    }

    /**
     * @param $client * Connection to mikrotik device
     * @param $dst_Address * Destination host
     * @return mixed * Object
     */
    protected function ping($client, $dst_Address)
    {
        $pingRequest = new Request('/ping count=3');
        $results = $client->sendSync(
            $pingRequest->setArgument('address', $dst_Address)
        );
        return $results;
    }

    /**
     * get version of mikrotik device
     * @param $client * Connection to mikrotik device
     * @return mixed * Object
     */
    protected function getVersion($client)
    {
        $responses = $client->sendSync(new Request('/system/resource/print'));

        foreach ($responses as $response) {
            if ($response->getType() === Response::TYPE_DATA) {
                return collect($response);
            }
            return null;
        }
    }

    public function enableProxy($connected, $enable, $port)
    {
        $addRequest = new Request('/ip proxy ' . 'set');
        $addRequest->setArgument('enabled', $enable);
        $addRequest->setArgument('port', $port);
        $addRequest->setArgument('max-fresh-time', '00:00:10');
        if (
            $connected->sendSync($addRequest)->getType() !==
            Response::TYPE_FINAL
        ) {
            return false;
        }
        return true;
    }

    /**
     * Get list from address list
     * @param $client * Connection to mikrotik device
     * @return array
     */
    protected function getAddressList($client)
    {
        $responses = $client->sendSync(
            new Request('/ip/firewall/address-list/print')
        );
        $data = [];
        $count = 0;
        foreach ($responses as $response) {
            if ($response->getType() === Response::TYPE_DATA) {
                foreach ($response as $name => $value) {
                    $data[$count][$name] = $value;
                }
            }
            $count++;
        }
        return $data;
    }

    protected function getPpoeClients($connected)
    {
        $responses = $connected->sendSync(new Request('/ppp/secret/print'));
        $data = [];
        $count = 0;
        foreach ($responses as $response) {
            if ($response->getType() === Response::TYPE_DATA) {
                foreach ($response as $name => $value) {
                    $data[$count][$name] = $value;
                }
            }
            $count++;
        }
        return $data;
    }

    /**
     * @param $client * Connection to mikrotik device
     * @param string $queue * name queue
     * @return int  * id
     */
    protected function getIfExistSimpleQueueByName(
        $client,
        $queue = 'SPEEDTEST'
    ) {
        $responses = $client->sendSync(new Request('/queue/simple/print'));

        return count(
            collect($responses)->filter(function ($value) use ($queue) {
                return isset(collect($value)['name']) &&
                    collect($value)['name'] == $queue;
            })
        );
    }

    /**
     * @param $client * Connection to mikrotik device
     * @return array * Queue list
     */
    protected function getAllSimpleQueue($client)
    {
        $responses = $client->sendSync(new Request('/queue/simple/print'));
        $data = [];
        $count = 0;
        foreach ($responses as $response) {
            if ($response->getType() === Response::TYPE_DATA) {
                foreach ($response as $name => $value) {
                    $data[$count][$name] = $value;
                }
            }
            $count++;
        }
        return $data;
    }

    /**
     * add the item in any instance of the mikrotik
     * @param $client * Connection to mikrotik device
     * @param $command * Constant with a place example: QUEUE_SIMPLE
     * @param $arrayArgumentValue * Argument required by the device instance example:
     * (['name'=>'ppoe-client-pepe',
     * 'user'=> 'pepe',
     * 'password'=>'Pass',
     * 'interface'=>'ether2',
     * 'service-name'=> '',
     * 'disabled'=>'no' ])
     * @return void
     */
    protected function addItem($client, $command, $arrayArgumentValue)
    {
        $addRequest = new Request($command . 'add');
        foreach ($arrayArgumentValue as $names => $value) {
            $addRequest->setArgument($names, $value);
        }
        if (
            $client->sendSync($addRequest)->getType() !== Response::TYPE_FINAL
        ) {
            Log::error($arrayArgumentValue);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Modificar un valor en un lugar
     * @param $client * Connection to mikrotik device
     * @param $command * Constant with a place example: QUEUE_SIMPLE_WHIT_SLASH
     * @param $id
     * @param $arrayArgumentValue $argument required by the device instance example:
     * (['name'=>'ppoe-client-pepe',
     * 'user'=> 'pepe',
     * 'password'=>'Pass',
     * 'interface'=>'ether2',
     * 'service-name'=> '',
     * 'disabled'=>'no' ])
     */
    protected function setvalueArrayById(
        $client,
        $command,
        $id,
        $arrayArgumentValue
    ) {
        $addRequest = new Request($command . 'set');
        $addRequest->setArgument('numbers', $id);

        foreach ($arrayArgumentValue as $names => $value) {
            $addRequest->setArgument($names, $value);
        }

        if (
            $client->sendSync($addRequest)->getType() !== Response::TYPE_FINAL
        ) {
            die('Error modificando');
        }
    }

    protected function setValueById($client, $command, $id, $names, $value)
    {
        $addRequest = new Request($command . 'set');
        $addRequest->setArgument('numbers', $id);
        $addRequest->setArgument($names, $value);

        if (
            $client->sendSync($addRequest)->getType() !== Response::TYPE_FINAL
        ) {
            return false;
        }
        return true;
    }

    /**
     * Remove item by id
     * @param $client * Connection to mikrotik device
     * @param $command * Constant with a place example: IP_FIREWALL_NAT_WHIT_SLASH
     * @param $name * name
     * @return $id
     */
    protected function removeById($client, $command, $id)
    {
        //$id now contains the ID of the entry we're targeting
        $setRequest = new Request($command . 'remove');
        $setRequest->setArgument('numbers', $id);
        $client->sendSync($setRequest);
    }

    public function deleteClientePpoe($connected, $name)
    {
        if ($this->getIdByName($connected, '/ppp/secret/', $name)) {
            $this->removeById(
                $connected,
                '/ppp/secret/',
                $this->getIdByName($connected, '/ppp/secret/', $name)
            );
        }
    }

    /**
     * Search queue type
     * @param $client * Connection to mikrotik device
     * @param $name * Queue name
     * @return bool * boolean
     */
    protected function getIfExistQueueType($client, $name)
    {
        if (empty($this->getIdByName($client, QUEUE_TYPE_WHIT_SLASH, $name))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * search by comment
     * @param $client * Connection to mikrotik device
     * @param $command * Constant with a place example: QUEUE_SIMPLE
     * @param $comment * instance comment
     * @return $id
     */
    protected function getIdByComment($client, $command, $comment)
    {
        $printRequest = new Request($command . 'print');
        $printRequest->setArgument('.proplist', '.id');
        $printRequest->setQuery(Query::where('comment', $comment));
        $id = $client->sendSync($printRequest)->getProperty('.id');
        return $id;
    }

    /**
     * Search by IP
     * @param $client * Connection to mikrotik device
     * @param $command * Constant with a place example: QUEUE_SIMPLE
     * @param $Ip * host Address
     * @return $id
     */
    protected function getIdByIp($client, $command, $IP)
    {
        $printRequest = new Request($command . 'print');
        $printRequest->setArgument('.proplist', '.id');
        $printRequest->setQuery(Query::where('address', $IP));
        $id = $client->sendSync($printRequest)->getProperty('.id');
        return $id;
    }

    /**
     * Search by Name
     * @param $client * Connection to mikrotik device
     * @param $command * Constant with a place example: QUEUE_SIMPLE
     * @param $name * instance name
     * @return $id
     */
    protected function getIdByName($client, $command, $name)
    {
        $printRequest = new Request($command . 'print');
        $printRequest->setArgument('.proplist', '.id');
        $printRequest->setQuery(Query::where('name', $name));
        $id = $client->sendSync($printRequest)->getProperty('.id');
        return $id;
    }

    /**
     * Search by Name
     * @param $client * Connection to mikrotik device
     * @param $command * Constant with a place example: QUEUE_SIMPLE
     * @param $name * instance name
     * @return $id
     */
    protected function getIdByServiceName($client, $command, $name)
    {
        $printRequest = new Request($command . 'print');
        $printRequest->setArgument('.proplist', '.id');
        $printRequest->setQuery(Query::where('service-name', $name));
        $id = $client->sendSync($printRequest)->getProperty('.id');
        return $id;
    }

    protected function getIdByargument(
        $client,
        $command,
        $argument,
        $value,
        $out
    ) {
        $printRequest = new Request($command . 'print');
        $printRequest->setArgument('.proplist', $out);
        $printRequest->setQuery(Query::where($argument, $value));
        $id = $client->sendSync($printRequest)->getProperty($out);
        return $id;
    }

    protected function getTargetByName($client, $command, $name)
    {
        $printRequest = new Request($command . 'print');
        $printRequest->setArgument('.proplist', 'target');
        $printRequest->setQuery(Query::where('name', $name));
        $target = $client->sendSync($printRequest)->getProperty('target');
        return $target;
    }

    protected function setIpToClientByAssignmentMethod($modelInternetService)
    {
        $method = $modelInternetService->ipv4_assignment;
        $doActionByMethod = [
            'IP Estatica' => 'returnStaticIp',
            'Pool IP' => 'returnPoolIp',
        ];
        if (isset($doActionByMethod[$method])) {
            $run = $doActionByMethod[$method];
            return $this->$run($modelInternetService);
        }
        return null;
    }

    public function returnStaticIp($modelInternetService)
    {
        $id = $modelInternetService['ipv4'];
        $client_id = $modelInternetService['client_id'];
        $ipPool = NetworkIp::find($id);
        try {
            $ipPool->update([
                'used' => 1,
                'used_by' => $modelInternetService->id,
                'client_id' => $modelInternetService->client_id,
            ]);
            return $ipPool->ip;
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    public function returnPoolIp($modelInternetService)
    {
        $pool_id = $modelInternetService->ipv4_pool;
        $router_id = $modelInternetService->router_id;
        $client_id = $modelInternetService->client_id;

        $this->isNotExistPoolInMikrotik($router_id, $pool_id);
        return $this->selectAndSetIpInPool(
            $pool_id,
            $client_id,
            $modelInternetService->id
        );
    }

    protected function selectAndSetIpInPool($id, $client_id, $serviceId)
    {
        $ipPool = NetworkIp::where('network_id', $id)
            ->where('used', '=', false)
            ->first();

        $ipPool->update([
            'used' => 1,
            'used_by' => $serviceId,
            'client_id' => $client_id,
        ]);
        return $ipPool->id;
    }

    protected function isNotExistPoolInMikrotik($routerid, $Poolid)
    {
        $pool = Network::find($Poolid);
        $poolName = $pool->title;
        $poolComment = $pool->comment;

        $router = Router::where('id', $routerid)
            ->with('mikrotik')
            ->first();
        $device_ip = $router->ip_host;
        $device_login = $router->mikrotik->login_api;
        $device_password = $router->mikrotik->password_api;
        $device_port = $router->mikrotik->port_api;

        $Ipv4Calculator = new Ipv4CalculatorController();
        $ranges = $Ipv4Calculator->getRangesIP($pool->network, $pool->bm);

        $connected = $this->connection(
            $device_ip,
            $device_login,
            $device_password,
            $device_port
        );
        if ($connected) {
            if (!$this->getIdByName($connected, '/ip/pool/', $poolName)) {
                $this->addItem($connected, '/ip pool ', [
                    'name' => $poolName,
                    'ranges' => collect($ranges)->implode('-'),
                    'comment' => $poolComment,
                ]);
            }
        }
    }

    /**
     * @param $connection
     * @return \Illuminate\Support\Collection
     ['$.id',
     '$name',
     '$service',
     '$caller-id',
     '$address',
     '$uptime',
     '$encoding',
     '$session-id',
     '$limit-bytes-in',
     '$limit-bytes-out',
     '$radius',
     '$comment']
     */
    protected function getAllClientPppWithActiveConnection($connection)
    {
        $ppps = $connection
            ->sendSync(new Request('/ppp/active/print'))
            ->getAllOfType(Response::TYPE_DATA);
        return collect($ppps)->map(function ($val) {
            return collect($val)->toArray();
        });
    }

    protected function getAllClientSimpleQueues($connection)
    {
        $ppps = $connection
            ->sendSync(new Request('/queue/simple/print'))
            ->getAllOfType(Response::TYPE_DATA);
        return collect($ppps)->map(function ($val) {
            return collect($val)->toArray();
        });
    }

    public function addWebProxyAccessIpsPermited($connected, $ip_permited)
    {
        $ips = explode(',', $ip_permited);
        foreach ($ips as $ip) {
            $comment = 'MgNet_ACCESS_IP_PERMITED-' . $ip;
            if (
                !$this->getIdByComment(
                    $connected,
                    '/ip/proxy/access/',
                    $comment
                )
            ) {
                $this->addItem($connected, '/ip proxy access ', [
                    'action' => 'allow',
                    'dst-address' => $ip,
                    'comment' => $comment,
                ]);
            }
        }
    }

    public function addWebProxyAccessIpRedirect($connected, $ip_redirect)
    {
        $comment = 'MgNet_ACCESS_IP_REDIRECT';
        if (!$this->getIdByComment($connected, '/ip/proxy/access/', $comment)) {
            $this->addItem($connected, '/ip proxy access ', [
                'action' => 'allow',
                'dst-address' => $ip_redirect,
                'comment' => $comment,
            ]);
        }
    }

    public function addWebProxyAccessUrlRedirect($connected, $url_redirect)
    {
        $comment = 'MgNet_ACCESS_URL_REDIRECT';
        if (!$this->getIdByComment($connected, '/ip/proxy/access/', $comment)) {
            $this->addItem($connected, '/ip proxy access ', [
                'action' => 'deny',
                'redirect-to' => $url_redirect,
                'comment' => $comment,
            ]);
        }
    }

    public function addPpoeProfile($connected, $router)
    {
        if (
            !$this->getIdByName(
                $connected,
                '/ppp/profile/',
                $router->mikrotikconfig->mikrotik_config_server_pppoe_profile
            )
        ) {
            $this->addItem($connected, '/ppp profile ', [
                'name' =>  $router->mikrotikconfig->mikrotik_config_server_pppoe_profile,
                'local-address' =>
                $router->mikrotikconfig->mikrotik_config_server_ppp_local_address,
                'remote-address' =>
                $router->mikrotikconfig->mikrotik_config_server_ppp_remote_address,
                'bridge' =>$router->mikrotikconfig->mikrotik_config_server_ppp_bridge,
                'dns-server' => '8.8.8.8,8.8.4.4',
            ]);
        }
    }

    public function addServerRadius($connected, $router)
    {
        if (
            !$this->getIdByComment(
                $connected,
                '/radius ',
                'MgNet_Radius_Service'
            )
        ) {
            $this->addItem($connected, '/radius ', [
                'service' => 'ppp,login',
                'secret' => $router->secret_radius,
                'address' => $router->nas_ip,
                'protocol' => 'udp',
                'comment' => 'MgNet_Radius_Service',
            ]);
        }
    }

    public function addServerPppoe($connected, $router)
    {
        if (
            !$this->getIdByServiceName(
                $connected,
                '/interface/pppoe-server/server/',
                $router->mikrotikconfig->mikrotik_config_server_pppoe_name
            )
        ) {
            $this->addItem($connected, '/interface pppoe-server server ', [
                'service-name' => $router->mikrotikconfig->mikrotik_config_server_pppoe_name,
                'interface' => $router->mikrotikconfig->mikrotik_config_server_pppoe_interface,
                'max-mtu' => $router->mikrotikconfig->mikrotik_config_server_pppoe_mtu,
                'max-mru' => $router->mikrotikconfig->mikrotik_config_server_pppoe_mru,
                'default-profile' =>
                        $router->mikrotikconfig->mikrotik_config_server_pppoe_profile,
                'one-session-per-host' => 'yes',
                'authentication' => 'mschap2, mschap1, chap, pap',
                'disabled' => 'no',
            ]);
        }
    }

    public function updatePpoeProfile($connected, $mikrotikConfig, $namePppProfile)
    {
        $id = $this->getIdByName(
            $connected,
            '/ppp/profile/',
            $namePppProfile
        );
        if ($id) {
            $this->setvalueArrayById($connected, '/ppp/profile/', $id, [
                'name' =>
                    $mikrotikConfig->mikrotik_config_server_pppoe_profile,
                'local-address' =>
                    $mikrotikConfig->mikrotik_config_server_ppp_local_address,
                'remote-address' =>
                    $mikrotikConfig->mikrotik_config_server_ppp_remote_address,
                'bridge' =>
                    $mikrotikConfig->mikrotik_config_server_ppp_bridge,
                'dns-server' => '8.8.8.8,8.8.4.4',
            ]);
        }
    }

    public function updateServerPppoe($connected, $mikrotikConfig, $nameServerPpoe)
    {
        $id = $this->getIdByServiceName(
            $connected,
            '/interface/pppoe-server/server/',
            $nameServerPpoe
        );
        if ($id) {
            $this->setvalueArrayById(
                $connected,
                '/interface/pppoe-server/server/',
                $id,
                [
                    'service-name' =>
                    $mikrotikConfig->mikrotik_config_server_pppoe_name,
                    'interface' =>
                        $mikrotikConfig->mikrotik_config_server_pppoe_interface,
                    'max-mtu' =>
                        $mikrotikConfig->mikrotik_config_server_pppoe_mtu,
                    'max-mru' =>
                        $mikrotikConfig->mikrotik_config_server_pppoe_mru,
                    'default-profile' =>
                        $mikrotikConfig->mikrotik_config_server_pppoe_profile,
                    'one-session-per-host' => 'yes',
                    'authentication' => 'mschap2, mschap1, chap, pap',
                    'disabled' => 'no',
                ]
            );
        }
        return false;
    }

    public function updateNatRulesMorososRedirct($connected, $router)
    {
        $comment = 'MgNet_REDIRECT_MOROSOS_TO_WEB_PROXY';
        $id = $this->getIdByComment($connected, '/ip/firewall/nat/' , $comment);
        if ($id) {
            $this->setvalueArrayById($connected, '/ip/firewall/nat/', $id,
                [
                    'chain' => 'dstnat',
                    'protocol' => 'tcp',
                    'dst-port' => '80',
                    'action' => 'redirect',
                    'to-ports' => $router->mikrotik->port_redirect,
                    'src-address-list' => 'MgNet_Morosos',
                    'comment' => $comment
                ]);
        }
    }

    public function updateServerRadius($connected, $router)
    {
        $id = $this->getIdByComment($connected, '/radius/', 'MgNet_Radius_Service');
        if ($id) {
            $this->setvalueArrayById($connected, '/radius/ ', $id,
                [
                    'service' => 'ppp,login',
                    'secret' => $router->secret_radius,
                    'address' => $router->nas_ip,
                    'protocol' => 'udp',
                    'comment' => 'MgNet_Radius_Service'
                ]);
        }
    }

    public function updateWebProxyAccessIpRedirect($connected, $ip_redirect, $router)
    {
        $comment = 'MgNet_ACCESS_IP_REDIRECT';
        $id = $this->getIdByComment($connected, '/ip/proxy/access/', $comment);
        if ($id) {
            $this->setvalueArrayById($connected, '/ip/proxy/access/', $id, ([
                'action' => 'allow',
                'dst-address' => $ip_redirect,
                'comment' => $comment
            ]));
        }
    }

    public function updateWebProxyAccessUrlRedirect($connected, $url_redirect)
    {
        $comment = 'MgNet_ACCESS_URL_RREDIRECT';
        $id = $this->getIdByComment($connected, '/ip/proxy/access/', $comment);
        if ($id) {
            $this->setvalueArrayById($connected, '/ip/proxy/access/', $id, ([
                'action' => 'deny',
                'redirect-to' => $url_redirect,
                'comment' => $comment
            ]));
        }
    }

    public function updateWebProxyAccessIpsPermited($connected, $ip_permited)
    {
        $ips = explode(',', $ip_permited);
        foreach ($ips as $ip) {
            $comment = 'MgNet_ACCESS_IP_PERMITED-' . $ip;
            $id = $this->getIdByComment($connected, '/ip/proxy/access/', $comment);
            if ($id) {
                $this->setvalueArrayById($connected, '/ip/proxy/access/', $id, ([
                    'action' => 'allow',
                    'dst-address' => $ip,
                    'comment' => $comment
                ]));
            }
        }
    }

    public function addRulesInputApiAccept($connected, $command, $srcAddress, $dstPort)
    {
        $comment = 'MgNet_INPUT_MEGANET_TO_API_ACCEPT';
        if (!$this->getIdByComment($connected, $command, $comment)) {
            $this->addItem($connected, '/ip firewall filter ', [
                'chain' => 'input',
                'action' => 'accept',
                'src-address' => $srcAddress,
                'dst-address' => null,
                'dst-address-type' => null,
                'protocol' => 'tcp',
                'src-port' => null,
                'dst-port' => $dstPort,
                'port' => null,
                'in-interface' => null,
                'out-interface' => null,
                'src-address-list' => null,
                'dst-address-list' => null,
                'connection-state' => null,
                'comment' => $comment,
            ]);
        }
    }

    public function updateRulesInputApiAccept($connected, $routerPort_api, $mikrotikconfig)
    {
        $comment = 'MgNet_INPUT_MEGANET_TO_API_ACCEPT';
        $id = $this->getIdByComment($connected, '/ip/firewall/filter/', $comment);
        if ($id) {
            $this->setvalueArrayById($connected, '/ip/firewall/filter/', $id, ([
                'chain' => 'input',
                'action' => 'accept',
                'src-address' => $mikrotikconfig->meganet_config_ip_address,
                'dst-address' => null,
                'dst-address-type' => null,
                'protocol' => 'tcp',
                'src-port' => null,
                'dst-port' => $routerPort_api,
                'port' => null,
                'in-interface' => null,
                'out-interface' => null,
                'src-address-list' => null,
                'dst-address-list' => null,
                'connection-state' => null,
                'comment' => $comment
            ]));
        }
    }

    public function removeIpSerparatedbyComma($connected, $IpSerparatedbyComma)
    {
        $ips = explode(',', $IpSerparatedbyComma);
        foreach ($ips as $ip) {
            $this->removeById($connected, '/ip/proxy/access/',
                $this->getIdByComment($connected, '/ip/proxy/access/', 'MgNet_ACCESS_IP_PERMITED-' . $ip));
        }
    }

    protected function getCount($client, $command)
    {
        $request = new Request($command);
        $request->setArgument('count-only', '');
        return collect(collect($client->sendSync($request))->first())['ret'];
    }

    protected function removeAll($client, $command){
    $items = $client->sendSync(new Request($command. 'print'))->getAllOfType(Response::TYPE_DATA);

    foreach ($items  as $item) {
        $id = $item('.id');
        $this->removeById($client, $command, $id);
        }
    }

    protected function fileExport($client){
        $exportFileName = 'EXPORT.rsc';
        $util = new Util($client);
        $client->sendSync($util->newRequest('export', array('file' => $exportFileName)));
        sleep(2);
        $export = $util->fileGetContents($exportFileName);
        $util->filePutContents($exportFileName, null);//Optional; Remove the file from the router
        return $export;
    }
}
