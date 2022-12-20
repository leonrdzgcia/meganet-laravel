<?php

namespace App\Observers;

use App\Jobs\CreateClientWithServiceJob;
use App\Models\NetworkIp;
use App\Models\ClientInternetService;
use App\Jobs\UpdateClientWithServiceJob;
use App\Jobs\DeletedClientWithServiceJob;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\RouterConnection;

class ClientInternetServiceObserver
{
use RouterConnection;

    public function creating(ClientInternetService $clientInternetService){
        $client_services = \App\Models\Client::where('id',$clientInternetService->client_id)->haveServices()->first();
        if(!$client_services){
            \App\Models\ClientMainInformation::where('client_id',$clientInternetService->client_id)->update(['estado' => 'Bloqueado']);
        }
    }

    public function created(ClientInternetService $clientInternetService)
    {
       $this->setIpToClientByAssignmentMethod($clientInternetService);
    }

    public function updating(ClientInternetService $clientInternetService)
    {
        UpdateClientWithServiceJob::dispatchAfterResponse($clientInternetService);

        $networkIpOld = NetworkIp::find($clientInternetService->getOriginal('ipv4'));
        if ($networkIpOld) {
            $networkIpOld->update(['used' => false, 'used_by' => '--', 'client_id' => null]);
        }

        $networkIpNew = NetworkIp::find($clientInternetService->ipv4);
        if ($networkIpNew) {
            $networkIpNew->update(['used' => true, 'used_by' => $clientInternetService->id , 'client_id' => $clientInternetService->client_id]);
        }
    }

    /**
     * Handle the ClientInternetService "deleted" event.
     *
     * @param \App\Models\ClientInternetService $clientInternetService
     * @return void
     */
    public function deleting(ClientInternetService $clientInternetService)
    {
        DeletedClientWithServiceJob::dispatch($clientInternetService);
        // $networkIp = NetworkIp::find($clientInternetService->ipv4);
        // if ($networkIp) {
        //     $networkIp->update(['used' => false, 'used_by' => '--', 'client_id' => null]);
        // }
    }
}
