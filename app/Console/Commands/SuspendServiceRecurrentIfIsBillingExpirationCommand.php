<?php

namespace App\Console\Commands;

use App\Jobs\Mikrotik\MikrotikCreateAddressList;
use App\Models\ClientInternetService;
use App\Models\TypeBilling;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SuspendServiceRecurrentIfIsBillingExpirationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suspendservicerecurrentifisbillingexpiration:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $clientServices = ClientInternetService::with('router.mikrotik', 'network_ip.network','client.billing_configuration')
            ->leftJoin('clients', 'client_internet_services.client_id', '=', 'clients.id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->select('client_internet_services.*')
            ->whereDoesntHave('service_in_address_list')
            ->whereHas('client.client_main_information', function ($query) {
                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
            })
            ->whereHas('client.billing_configuration', function ($query) {
                $query->where('billing_date', (integer)Carbon::now()->format('d'));
            })
            ->whereDoesntHave('transactions', function ($query) {
                $query->whereRaw('DATE(created_at) >= DATE(DATE_SUB(now(), INTERVAL billing_configurations.period MONTH))');
            })
            ->get();

        foreach ($clientServices as $clientService) {
            $clientService->service_in_address_list()->create(['deployed' => true]);
            MikrotikCreateAddressList::dispatchAfterResponse($clientService);
        }
    }
}
