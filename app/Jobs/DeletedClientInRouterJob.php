<?php

namespace App\Jobs;

use App\Models\ClientMainInformation;
use App\Models\Router;
use App\Models\ClientInternetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\RouterConnection;

class DeletedClientInRouterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RouterConnection;
    
    protected $clientInternetService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $module)
    {
        $this->clientInternetService =  $this->getModelInternetService($model, $module); 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $router = Router::find($this->clientInternetService->router_id);
        $mikrotik = $router->mikrotik()->first();

        if ($mikrotik) {
            $connected = $this->initConnection($mikrotik, $router->ip_host);
            $this->deleteClientePpoe( $connected, $this->clientInternetService->client_name);
            $this->clientInternetService->client()->first()->mikrotik_client_ppoe()->delete();
            $this->clientInternetService->network_ip()->update([
                'used' => false,
                'used_by' => '--',
                'ping' => 'Desconocido',
                'host_category' => 'Ninguno',
            ]);
        }
    }

    public function getModelInternetService ($model , $module){
       if ($module == 'ClientInternetService') {
        return $model;
        }

        if ($module == 'ClientMainInformation'){
            return ClientInternetService::where('client_id',$model->client_id)
            ->first();
        }
      return null;
    }

}
