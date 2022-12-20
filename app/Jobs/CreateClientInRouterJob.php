<?php

namespace App\Jobs;

use App\Models\ClientInternetService;
use App\Models\MikrotikClientPpoe;
use App\Models\NetworkIp;
use App\Models\Router;
use App\Models\ServiceInAddressList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Traits\RouterConnection;

class CreateClientInRouterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RouterConnection;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = 'App\Models\Internet';
        $mikrotikClientPpoes = MikrotikClientPpoe::all();
        foreach ($mikrotikClientPpoes as $mikrotikClientPpoe) {
            $clientService = ClientInternetService::with('router')->where('client_id', $mikrotikClientPpoe->client_id)->first();
            $client = \App\Models\Client::find($mikrotikClientPpoe->client_id);
            if ($clientService) {
                $router = Router::find($clientService->router_id);
                $device_ip = $router->ip_host;
                $mikrotik = $router->mikrotik()->first();
                $connection = $this->initConnection($mikrotik, $device_ip);

                $login = $client->clientGetUser();
                $password = $client->clientGetPassword();
                $clientIp = NetworkIp::find($clientService->ipv4);
                $comment = 'Meganet_' . $clientService->id;

                if ($connection) {
                    if (!$this->getIdByName($connection, '/ppp/secret/', $login)) {
                        $this->addItem($connection, '/ppp secret ', [
                            'name' => $login,
                            'password' => $password,
                            'service' => 'any',
                            'profile' => 'default',
                            'remote-address' => $clientIp->ip,
                            'disabled' => 'no',
                            'comment' => $comment
                        ]);
                    }
                }
            }
        }

        $accessLists = ServiceInAddressList::all();
        foreach ($accessLists as $accessList) {

            $clientService = ClientInternetService::find($accessList->serviceable_id);
            if ($clientService) {
                $clientName = $clientService->client_name;

                $clientId = $clientService->client_id;

                $router = Router::find($clientService->router_id);
                $device_ip = $router->ip_host;
                $mikrotik = $router->mikrotik()->first();
                $clientIp = NetworkIp::find($clientService->ipv4)->ip;
                $connection = $this->initConnection($mikrotik, $device_ip);
                if ($connection) {
                    $this->addItem($connection, '/ip/firewall/address-list/', (['list' => 'MgNet_Morosos', 'address' => $clientIp, 'comment' => $clientName . '-' . $clientId]));
                }
            }
        }
    }
}
