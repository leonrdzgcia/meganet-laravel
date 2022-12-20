<?php

namespace App\Console\Commands;

use App\Models\ClientInternetService;
use App\Models\TypeBilling;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\Client\RemoveServiceFromAddressList;

class RemoveServiceFromAddressListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'removeservicefromaddresslist:process';

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
        // PREPAID CUSTOM
        $clientServices = ClientInternetService::with('router.mikrotik')
            ->leftJoin('internets', 'client_internet_services.internet_id', '=', 'internets.id')
            ->whereHas('client.client_main_information', function ($query) {
                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
            })
            ->where('estado', '=', 'Activado')
            ->where('deployed', '=', 1)
            ->where('charged', '=', 1)
            ->whereHas('service_in_address_list')
            ->where(function ($query) {
                //Daily
                $query->where(function ($query) {
                    $query->whereHas('client.client_main_information', function ($query) {
                        $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY);
                    })->whereHas('client.balance', function ($query) {
                        $query->whereRaw('amount >= (internets.price / ' . Carbon::now()->daysInMonth . ')');
                    });
                })//Custom
                ->orWhere(function ($query) {
                    $query->whereHas('client.client_main_information', function ($query) {
                        $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
                    })->whereHas('client.balance', function ($query) {
                        $query->whereRaw('amount >= internets.price');
                    });
                })//Recurrent
                ->orWhere(function ($query) {
                    $query->whereHas('client.client_main_information', function ($query) {
                        $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
                    })->whereHas('client.balance', function ($query) {
                        $query->where('amount', '>=', 0);
                    });
                });
            })
            ->get();

        foreach ($clientServices as $clientService) {
            RemoveServiceFromAddressList::dispatchAfterResponse($clientService);
        }
    }
}
