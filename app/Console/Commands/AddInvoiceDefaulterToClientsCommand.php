<?php

namespace App\Console\Commands;

use App\Http\Repository\ClientRepository;
use App\Models\ClientInternetService;
use App\Models\Payment;
use App\Models\TypeBilling;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\ClientInvoice;

class AddInvoiceDefaulterToClientsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addinvoicedefaultertoclients:process';
    private $clientRepository;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Invoice Defaulter To Custom Clients when dont pay in time and they exceed a month and 1 day';

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
        $clientServices = ClientInternetService::with('client.balance', 'client.client_main_information', 'client.billing_configuration')
            ->leftJoin('clients', 'client_internet_services.client_id', '=', 'clients.id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')
            ->select('client_internet_services.*')
            ->where('estado', '=', 'Activado')
            ->where('charged', '=', 1)
            ->where('deployed', '=', 1)
            ->whereNull('client_bundle_service_id')
            ->where(function ($query) {
                //Daily
                $query->where(function ($query) {
                    $query->whereHas('client.client_main_information', function ($query) {
                        $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY);
                    })
                        ->whereDoesntHave('client_payment_service', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        })
                        ->whereDoesntHave('transactions', function ($query) {
                            $query->whereDate('created_at', Carbon::now()->toDateString());
                        })
                    ->whereDoesntHave('client.client_invoices', function ($query) {
                        $query->where('type', ClientInvoice::TYPE_INVOICE_SURCHARGE_DEFAULTER)
                              ->whereDate('created_at', Carbon::now()->toDateString());
                    });
                })
                    //Custom
                    ->orWhere(function ($query) {
                        $query->whereHas('client.client_main_information', function ($query) {
                            $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
                        })->whereDoesntHave('client_payment_service', function ($query) {
                            $query->whereRaw('DATE(created_at) >= DATE_ADD(DATE_SUB(now(), INTERVAL 1 MONTH), INTERVAL 1 DAY)');
                        })
                        ->whereDoesntHave('client.client_invoices', function ($query) {
                            $query->where('type', ClientInvoice::TYPE_INVOICE_SURCHARGE_DEFAULTER)
                                  ->whereDate('created_at', Carbon::now()->toDateString());
                        });
                    });
                 })
                 ->get();

        $this->ifNotClientRecurrentAndAsLogTimeToAddInvoiceDefaulter( $clientServices );
    }

    public function ifNotClientRecurrentAndAsLogTimeToAddInvoiceDefaulter( $clientServices ){
        foreach ($clientServices as $clientService){
            if ($clientService->count()) {
                $client = $clientService->client;
                $cost = 99.0;

                $newBalanceAndPrice = [
                    'new_balance' =>  $client->balance->amount - $cost,
                    'price' => $cost,
                    'cost' => $cost,
                    'payment_in_time' => null
                ];

                $invoice = $this->clientRepository->addInvoiceDefaulter( $client, $client->balance->amount - $cost >= 0 ,  $cost );
                $client->balance()->update(['amount' => $newBalanceAndPrice['new_balance']]);
                $this->clientRepository->addDebitTransactionForPaymentDefaulter($client, $cost, $newBalanceAndPrice, $invoice);
            }
        }
    }
}
