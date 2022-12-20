<?php

namespace App\Jobs\Mikrotik;

use App\Models\ClientMainInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Router;
use App\Models\Mikrotik;
use App\Http\Traits\RouterConnection;

class MikrotikCreateAddressList implements ShouldQueue
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
        $this->clientService = $clientService ?? null;
        $this->router = $this->clientService->router ?? null;
        $this->mikrotik = $this->router->mikrotik ?? null;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->clientService) {
            $clientName = $this->clientService->client->client_main_information->user;
            $clientId = $this->clientService->client->id;

            if ($this->router && $this->router->type_of_nas == 'Mikrotik') {
                if ($this->mikrotik) {
                    $connected = $this->initConnection($this->mikrotik, $this->router->ip_host);
                    if ($connected) {
                        if ($this->mikrotik->active) {
                            $clientIp = $this->clientService->network_ip->ip;
                            $this->addItem($connected,'/ip/firewall/address-list/',(['list'=>'MgNet_Morosos','address'=> $clientIp,'comment'=>$clientName. '-' .$clientId]));
                            $this->clientService->service_in_address_list()->updateOrCreate(['deployed' => true]);
                            ClientMainInformation::where('client_id', $this->clientService->client_id)->update(['estado' => 'Bloqueado']);
                        }
                    }
                }
            }
        }
    }
}
