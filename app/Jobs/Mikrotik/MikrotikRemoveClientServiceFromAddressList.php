<?php

namespace App\Jobs\Mikrotik;

use App\Models\ClientMainInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Traits\RouterConnection;
use Illuminate\Support\Facades\Log;

class MikrotikRemoveClientServiceFromAddressList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RouterConnection;

    protected $clientService;
    protected $router;
    protected $mikrotik;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($clientService)
    {
        $this->clientService = $clientService;
        $this->router = $this->clientService->router;
        $this->mikrotik = $this->router->mikrotik;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->router->isMikrotik() && $this->router->hasMikrotik() && $this->mikrotik->isActive()) {
            $connected = $this->initConnection($this->mikrotik, $this->router->ip_host);
            if ($connected) {
                $clientIp = $this->clientService->network_ip->ip ?? null;
                if ($clientIp){
                    $idByIp = $this->getIdByIp($connected,'/ip/firewall/address-list/', $clientIp);
                    if ($idByIp){
                        $this->removeById($connected, '/ip/firewall/address-list/', $idByIp);
                        $this->removeServiceInAdressListFromDb();
                        $this->setClientMainInformationToActive();
                    }
                }
            }
        }
    }

    public function removeServiceInAdressListFromDb()
    {
        $this->clientService->service_in_address_list()->delete();
    }

    public function setClientMainInformationToActive()
    {
        $clientMainInformation =  ClientMainInformation::where('client_id', $this->clientService->client_id)->first();
        if ( $clientMainInformation->estado != 'Activo'){
            $clientMainInformation->update(['estado' => 'Activo']);
        }
    }
}
