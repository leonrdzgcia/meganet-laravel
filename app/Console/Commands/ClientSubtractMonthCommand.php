<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\ClientBundleService;
use App\Models\ClientInternetService;
use App\Models\TypeBilling;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClientSubtractMonthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sustractmonth:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resta un mes a los atributos del cliente para que sea el dia del cobro';

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
        $id = $this->ask('Escribe id del Cliente?');
        if ($id) {
            $this->sustraerMesAlClient($id);
        } else {
            $this->info('Debe escribir el id de un cliente');
            $id = $this->ask('Escribe id del Cliente?');
            if ($id) {
                $this->sustraerMesAlClient($id);
            }
        }

    }

    public function sustraerMesAlClient($id)
    {
        $client = Client::find($id);
        $clientName = $client->client_main_information()->first()->client_name_with_fathers_names;
        $this->info("name: $clientName");

        if ($client->client_main_information()->first()->type_of_billing_id == TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM) {

            foreach ($client->internet_service()->get() as $internet) {
                $this->info("Servicios de internet");
                $this->info("id del servicio: $internet->id");
            }

            $id = $this->ask('Escribe id del servicio?');
            $clientInternetService = ClientInternetService::find($id);
            foreach ($clientInternetService->client_payment_service()->get() as $item) {
                $item->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->subMonths(1)->toDateTimeString();
                $item->save();
            }

            foreach ($client->transactions()->get() as $transaction) {
                $transaction->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->subMonths(1)->toDateTimeString();
                $transaction->date = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->date)->subMonths(1)->toDateTimeString();
                $transaction->save();
            }
            foreach ($clientInternetService->transactions()->get() as $transaction) {
                $transaction->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->subMonths(1)->toDateTimeString();
                $transaction->date = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->date)->subMonths(1)->toDateTimeString();
                $transaction->save();
            }

            $clientInternetService->update([
                'start_date' => Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->start_date)->subMonths(1)->toDateTimeString(),
                'finish_date' => Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->finish_date)->subMonths(1)->toDateTimeString(),
                'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->created_at)->subMonths(1)->toDateTimeString()
            ]);
        }

        if ($client->client_main_information()->first()->type_of_billing_id == TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT) {
            $this->info("Servicios de internet y paquetes");
            foreach ($client->bundle_service()->get() as $bundle) {
                $this->info("id del servicio: $bundle->id");
            }

            $id = $this->ask('Escribe id del paquete?');
            $clientInternetService = ClientBundleService::find($id);

            foreach ($client->transactions()->get() as $transaction) {
                $transaction->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->subMonths(1)->toDateTimeString();
                $transaction->date = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->date)->subMonths(1)->toDateTimeString();
                $transaction->save();
            }

            foreach ($clientInternetService->transactions()->get() as $transaction) {
                $transaction->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at)->subMonths(1)->toDateTimeString();
                $transaction->date = Carbon::createFromFormat('Y-m-d H:i:s', $transaction->date)->subMonths(1)->toDateTimeString();
                $transaction->save();
            }

            $clientInternetService->update([
                'contract_start_date' => Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->contract_start_date)->subMonths(1)->toDateTimeString(),
                'contract_end_date' => Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->contract_end_date)->subMonths(1)->toDateTimeString(),
                'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->created_at)->subMonths(1)->toDateTimeString()
            ]);
        }
    }

}
