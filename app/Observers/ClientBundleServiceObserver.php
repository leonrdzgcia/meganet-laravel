<?php

namespace App\Observers;

use App\Models\ClientBundleService;
use App\Models\ClientMainInformation;
use App\Models\NetworkIp;
use App\Jobs\UpdateClientWithServiceJob;
use Illuminate\Support\Facades\Log;
use App\Jobs\DeletedClientWithServiceJob;
use App\Http\Traits\RouterConnection;
use Mosquitto\Client;


class ClientBundleServiceObserver
{
    use RouterConnection;

    public function creating(ClientBundleService $clientBundleService){
        $client_services = \App\Models\Client::where('id',$clientBundleService->client_id)->haveServices()->first();
        if(!$client_services){
            \App\Models\ClientMainInformation::where('client_id',$clientBundleService->client_id)->update(['estado' => 'Bloqueado']);
        }
    }

    public function created(ClientBundleService $clientBundleService)
    {
        foreach ($clientBundleService->service_internet() as $clientInternetService) {
            $this->setIpToClientByAssignmentMethod($clientInternetService);
        }
    }


    public function updated(ClientBundleService $clientBundleService)
    {
        //  UpdateClientWithServiceJob::dispatchAfterResponse($clientInternetService);
    }

    /**
     * Handle the ClientBundleService "deleted" event.
     *
     * @param \App\Models\ClientBundleService $clientBundleService
     * @return void
     */
    public function deleting(ClientBundleService $clientBundleService)
    {
        $clientMainInformation = ClientMainInformation::find($clientBundleService->client_id);
        $clientMainInformation->estado = 'Bloqueado';
        $clientInternetService = $clientBundleService->service_internet()->first();
        if ($clientInternetService) {
            DeletedClientWithServiceJob::dispatch($clientInternetService);
            $networkIp = NetworkIp::find($clientInternetService->ipv4);
            if ($networkIp) {
                $networkIp->update(['used' => false, 'used_by' => '--', 'client_id' => null]);
            }
        }
    }

    public function bundleHasInternetService($input, $idClient)
    {
        $intenets = ClientBundleService::where('id', $input->bundle_id)
            ->with('planes_internet')
            ->whereHas('planes_internet')
            ->get();

        foreach ($intenets as $internet) {
            $ids = $internet->planes_internet->id;
        }

        $arrayToAsingnameIp = [];
        if ($ids) {
            foreach ($ids as $id) {
                $arrayToAsingnameIp[] = [
                    "ipv4" => $input['plan_internet_ipv4_' . $id],
                    "additional_ipv4" => $input['plan_internet_additional_ipv4_' . $id],
                    "ipv4_pool" => $input['plan_internet_ipv4_pool_' . $id],
                    "router_id" => $input['plan_internet_router_id_' . $id],
                    "ipv4_assignment" => $input['plan_internet_ipv4_assignment_' . $id],
                    "client_id" => $idClient
                ];
            }
        }
        return $arrayToAsingnameIp;
    }
}
