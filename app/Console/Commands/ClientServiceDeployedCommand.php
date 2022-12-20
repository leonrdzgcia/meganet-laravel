<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Client\DeployServiceFirstTime;
use App\Jobs\Mikrotik\CheckMikrotikConection;
use App\Jobs\Mikrotik\MikrotikCreateAddressList;
use App\Jobs\CreateClientWithServiceJob;
use App\Models\ClientInternetService;
use App\Models\ClientVozService;
use App\Models\ClientCustomService;
use App\Models\ClientBundleService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Jobs\Client\Invoice\ClientInvoiceJob;

class ClientServiceDeployedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientservicedeployed:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea servicio para clientes cuando el campo deployed(todavia sin desplegar) esta en false y charged(pagado por primera vez) en true';

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
    public function handle()
    {
        //TODO agregarle a la tabla sync los servicios por clientes que no tienen factura.

         $clientServices = ClientInternetService::with('client.balance', 'client.client_main_information', 'router.mikrotik')
            ->perteneceAClienteConBillingConfiguration()
            ->noEsteEnElAddressList()
            ->pendiente()
            ->noEstaCobrado()
            ->noEsteDesplegado()
            ->servicioNoPerteneceAUnPaquete()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->get();

        $model = 'App\Models\Internet';
        foreach ($clientServices as $clientService) {
            try {
                Bus::chain([
                    new CheckMikrotikConection($clientService),
                    new CreateClientWithServiceJob($clientService, $model),
                    new DeployServiceFirstTime($clientService),
                    new MikrotikCreateAddressList($clientService),
                ])->dispatch();
            } catch (\Exception $exception) {
                Log::info($exception);
            }
        }

        $clientServices = ClientVozService::with('client.balance', 'client.client_main_information')
            ->perteneceAClienteConBillingConfiguration()
            ->pendiente()
            ->noEstaCobrado()
            ->noEsteDesplegado()
            ->servicioNoPerteneceAUnPaquete()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->get();

        foreach ($clientServices as $clientService) {
            try {
                Bus::chain([
                    new DeployServiceFirstTime($clientService)
                ])->dispatch();
            } catch (\Exception $exception) {
                Log::info($exception);
            }
        }

        $clientServices = ClientCustomService::with('client.balance', 'client.client_main_information')
            ->perteneceAClienteConBillingConfiguration()
            ->pendiente()
            ->noEstaCobrado()
            ->noEstaDesplegado()
            ->servicioNoPerteneceAUnPaquete()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->get();

        foreach ($clientServices as $clientService) {
            try {
                Bus::chain([
                    new DeployServiceFirstTime($clientService)
                ])->dispatch();
            } catch (\Exception $exception) {
                Log::info($exception);
            }
        }

        $clientServices = ClientBundleService::with('client.balance', 'client.client_main_information', 'client.internet_service.router.mikrotik')
            ->perteneceAClienteConBillingConfiguration()
            ->pendiente()
            ->noEstaCobrado()
            ->noEsteDesplegado()
            ->queEsteEnUnPeriodoDeTiempoValido()
            ->get();

        foreach ($clientServices as $clientService) {
            $clientInternetServices = $clientService->client->internet_service;
            if ($clientInternetServices) {
                foreach ($clientInternetServices as $clientInternetService) {
                    try {
                        Bus::chain([
                            new CheckMikrotikConection($clientInternetService),
                            new CreateClientWithServiceJob($clientInternetService, $model),
                            new DeployServiceFirstTime($clientService),
                            new MikrotikCreateAddressList($clientInternetService),
                        ])->dispatch();
                    } catch (\Exception $exception) {
                        Log::info($exception);
                    }
                }
            }
            //TODO en caso de no existir servicio de internet montar los demas servicios del paquete
            else {
                try {
                    Bus::chain([
                        new DeployServiceFirstTime($clientService)
                    ])->dispatch();
                } catch (\Exception $exception) {
                    Log::info($exception);
                }
            }
        }
    }


    // TODO comando 12am
    /*
     * Dame todos los servicios que no tienen factura agrupados por cliente
     *
     *
     * */
}
