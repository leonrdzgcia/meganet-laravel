<?php

namespace App\Console\Commands;

use App\Http\Repository\ClientRepository;
use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;
use App\Models\Client;
use App\Models\TypeBilling;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\Client\ClientServiceChargedJob;
use App\Jobs\Client\BillingService\RectifyBalanceAndCreateTransactionInClientServiceFirstTime;
use App\Models\ClientInternetService;
use App\Models\ClientVozService;
use App\Models\ClientCustomService;
use App\Models\ClientBundleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ClientInvoice;

class ClientServiceToChargeIfDeployedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientservicetochargeifdeployed:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea los servicios que estan activos cuando el campo displayed esta en true';

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
    public function handle(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $clientInternetServices = ClientInternetService::with('client.balance', 'client.client_main_information', 'router.mikrotik', 'network_ip')
            ->perteneceAClienteConBillingConfiguration()
            ->tengaMikrotikAsignado()
            ->esteEnElAddressList()
            ->servicioNoPerteneceAUnPaquete()
            ->activo()
            ->desplegado()
            ->noEstaCobrado()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->get();

            $clientVozServices = ClientVozService::with('client.balance', 'client.client_main_information')
            ->perteneceAClienteConBillingConfiguration()
            ->activo()
            ->servicioNoPerteneceAUnPaquete()
            ->desplegado()
            ->noEstaCobrado()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->get();

            $clientCustomServices = ClientCustomService::with('client.balance', 'client.client_main_information')
            ->perteneceAClienteConBillingConfiguration()
            ->activo()
            ->servicioNoPerteneceAUnPaquete()
            ->desplegado()
            ->noEstaCobrado()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->get();

            $clientBundleServices = ClientBundleService::with('client.balance', 'client.client_main_information', 'service_internet.router.mikrotik')
            ->perteneceAClienteConBillingConfiguration()
            ->activo()
            ->desplegado()
            ->noEstaCobrado()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->queTengaServiciosDeInternetSinDesplegar()
            ->get();

            $services = [
                        $clientInternetServices,
                        $clientVozServices,
                        $clientCustomServices,
                        $clientBundleServices
            ];

    $clientIdInternetServices = $clientInternetServices->pluck('client_id');
    $clientIdVozServices = $clientVozServices->pluck('client_id');
    $clientIdCustomServices = $clientCustomServices->pluck('client_id');
    $clientIdBundleServices = $clientBundleServices->pluck('client_id');

    $clients = $this->getClientIds($clientIdInternetServices, $clientIdVozServices ,$clientIdCustomServices ,$clientIdBundleServices);

        foreach ($clients as $clientId){
            $client = Client::find($clientId);
            if ($client) {
                if ($this->noExisteFacturaConServiciosParaCliente($client)){
                    $invoice = $this->clientRepository->addInvoiceService($client->id, false);
                    foreach ($services as $service) {
                        $idServices =  $service->where('client_id', $client->id)->pluck('id');
                        foreach($idServices as $id) {
                            $invoice->client_invoice_service()->update([
                                'client_serviceable_id' => $id,
                                'client_serviceable_type' => get_class($service[0])
                            ]);
                        }
                    }
                }
            }
        }

        if ($clientInternetServices->count()) {
            foreach ($clientInternetServices as $clientInternetService) {
                try {
                    Bus::chain([
                        new RectifyBalanceAndCreateTransactionInClientServiceFirstTime('Internet', $clientInternetService),
                        new ClientServiceChargedJob($clientInternetService),
                        new MikrotikRemoveClientServiceFromAddressList($clientInternetService),
                    ])->dispatch();
                } catch (\Exception $exception) {
                    Log::info($exception);
                }
            }
        }

        foreach ($clientVozServices as $clientVozService) {
            try {
                Bus::chain([
                    new RectifyBalanceAndCreateTransactionInClientServiceFirstTime('Voise', $clientVozService),
                    new ClientServiceChargedJob($clientVozService),
                ])->dispatch();
            } catch (\Exception $exception) {
                Log::info($exception);
            }
        }

        foreach ($clientCustomServices as $clientCustomService) {
            try {
                Bus::chain([
                    new RectifyBalanceAndCreateTransactionInClientServiceFirstTime('Custom', $clientCustomService),
                    new ClientServiceChargedJob($clientCustomService)
                ])->dispatch();
            } catch (\Exception $exception) {
                Log::info($exception);
            }
        }

        foreach ($clientBundleServices as $clientBundleService) {
            $bundleClientInternetServices = $clientBundleService->client->internet_service;
            if ($bundleClientInternetServices->count()) {
                foreach ($bundleClientInternetServices as $bundleClientInternetService) {
                    try {
                        Bus::chain([
                            new RectifyBalanceAndCreateTransactionInClientServiceFirstTime('Bundle', $clientBundleService),
                            new ClientServiceChargedJob($clientBundleService),
                            new ClientServiceChargedJob($bundleClientInternetService),
                            new MikrotikRemoveClientServiceFromAddressList($bundleClientInternetService),
                        ])->dispatch();
                    } catch (\Exception $exception) {
                        Log::info($exception);
                    }
                }
            }
        }
    }

   public function getClientIds($clientIdInternetServices, $clientIdVozServices ,$clientIdCustomServices ,$clientIdBundleServices){
       $union = Arr::collapse([$clientIdInternetServices,$clientIdVozServices,$clientIdCustomServices,$clientIdBundleServices]);
       return array_unique( $union,SORT_NUMERIC);
   }

    public function getInvoiceId($clientId){
    return Client::find($clientId)->lastInvoice()->id;
   }

    public function noExisteFacturaConServiciosParaCliente($client)
    {
     return $this->clientCustomAndRecurrente($client);
     //daily

    }

    public function clientCustomAndRecurrente($client){
        $client = Client::with('client_main_information', 'client_invoices')
            ->whereHas('client_main_information',function ($query) {
                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT)
                    ->orWhere('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
            })
            ->where('id',$client->id)
            ->first();

        $invoice = $client->client_invoices()
            ->where('payment', 0)
            ->where(DB::raw('DATE_FORMAT(created_at,"%m")'), Carbon::now()->format('m'))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($invoice) {
            $invoiceServices = $invoice->client_invoice_service;
        } else {
            return true;
        }

        $services = ["bundle_service", "internet_service", "voz_service", "custom_service"];

        if ($client) {
            $activeServices = $this->clientRepository->getServiceActive($client->id);
            foreach ($services as $service) {
                foreach ($activeServices->$service as $activeService) {
                    $invoiceService = $invoiceServices->where('client_serviceable_id',$activeService->id);
                    if ($invoiceService->count()) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }
    }


}
