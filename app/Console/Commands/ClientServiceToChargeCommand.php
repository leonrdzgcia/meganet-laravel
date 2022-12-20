<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Client\ClientServiceChargedJob;
use App\Jobs\Client\BillingService\RectifyBalanceAndCreateTransactionInClientService;
use App\Models\ClientInternetService;
use App\Models\ClientVozService;
use App\Models\ClientCustomService;
use App\Models\ClientBundleService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ClientServiceToChargeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientservicetocharge:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea los servicios que estan activos cuando el campo displayed esta en false';

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
        //TODO No se esta utilizand por el momento
        $clientServices = ClientInternetService::with('client.balance', 'client.client_main_information', 'router')
            ->whereHas('client.billing_configuration')
            ->where('estado', '=', 'Pendiente')
            ->where('charged', '=', 0)
            ->where('deployed', '=', 0)
            ->whereNull('client_bundle_service_id')
            ->where('start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
            ->where(function ($query) {
                $query->whereNull('finish_date')
                    ->orWhere('finish_date', '>=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'));
            })
            ->get();

        foreach ($clientServices as $clientService) {
            try {
                Bus::chain([
                    new RectifyBalanceAndCreateTransactionInClientService('Internet', $clientService),
                    new ClientServiceChargedJob($clientService)
                ])->dispatch();
            } catch (\Exception $exception) {
                Log::info($exception);
            }
        }

        $clientServices = ClientVozService::with('client.balance', 'client.client_main_information')
        ->whereHas('client.billing_configuration')
        ->where('estado', '=', 'Activado')
        ->where('charged', '=', 0)
        ->where('deployed', '=', 0)
        ->whereNull('client_bundle_service_id')
        ->where('start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
        ->where(function ($query) {
            $query->whereNull('finish_date')
                ->orWhere('finish_date', '>=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'));
        })
        ->get();

        foreach ($clientServices as $clientService) {
            try {
                Bus::chain([
                    new RectifyBalanceAndCreateTransactionInClientService('Voise',$clientService),
                    new ClientServiceChargedJob($clientService)
                ])->dispatch();
            }catch (\Exception $exception){
                Log::info($exception);
            }
        }

        $clientServices = ClientCustomService::with('client.balance', 'client.client_main_information')
        ->whereHas('client.billing_configuration')
        ->where('estado', '=', 'Activado')
        ->where('charged', '=', 0)
        ->where('deployed', '=', 0)
        ->whereNull('client_bundle_service_id')
        ->where('start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
        ->where(function ($query) {
            $query->whereNull('finish_date')
                ->orWhere('finish_date', '>=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'));
        })
        ->get();

        foreach ($clientServices as $clientService) {
            try {
                Bus::chain([
                     new RectifyBalanceAndCreateTransactionInClientService('Custom',$clientService),
                     new ClientServiceChargedJob($clientService)
                ])->dispatch();
            }catch (\Exception $exception){
                Log::info($exception);
            }
        }

        $clientServices = ClientBundleService::with('client.balance', 'client.client_main_information')
        ->whereHas('client.billing_configuration')
        ->where('estado', '=', 'Pendiente')
        ->where('charged', '=', 0)
        ->where('deployed', '=', 0)
        ->where('contract_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
        ->where(function ($query) {
            $query->whereNull('contract_end_date')
                ->orWhere('contract_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'));
        })
        ->get();

        foreach ($clientServices as $clientService) {
            try {
                Bus::chain([
                    new RectifyBalanceAndCreateTransactionInClientService('Bundle',$clientService),
                    new ClientServiceChargedJob($clientService)
                ])->dispatch();
            }catch (\Exception $exception){
                Log::info($exception);
            }
        }
    }
}
