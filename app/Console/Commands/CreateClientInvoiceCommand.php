<?php

namespace App\Console\Commands;

use App\Http\Controllers\Module\Client\ClientBillingConfigurationController;
use App\Http\Repository\ClientRepository;
use App\Models\Client;
use App\Models\TypeBilling;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class CreateClientInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createinvoice:process {--d|daily} {--clientid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea facturas para los diferentes tipos de clientes (recurrentes y custom sin pasar parametros) y daily pasando parametro ejemplo: createinvoice:process -d --clientid=1';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ClientRepository $clientRepository)
    {
        // TODO comando 12am
        /*
         * Dame todos los servicios que no tienen factura agrupados por cliente
         *
         *
         * */

        $this->clientRepository = $clientRepository;
        $clients = null;

        if (!$this->option('daily')) {
            $clients = Client::with(
                'client_main_information',
                'billing_configuration',
                'internet_service',
                'voz_service',
                'custom_service',
                'bundle_service',
        )
                ->elClienteNoTieneFacturaCreadaEsteMes()
                ->tipoRecurrenteYElDiaDeFacturacionSeaHoy()
                ->oSeaDeTipoCustomYTocaFacturarHoy()
                ->get();
        } else {
            $clientId = $this->option('clientid');
            $this->clientRepository->addInvoiceService($clientId, $this->estaPagada($clients));
        }

        if ($clients){
            foreach ($clients as $client) {
                if ($client) {
                    $this->clientRepository->addInvoiceService($client->id, $this->estaPagada($clients));
                }
            }
        }
    }

    public function estaPagada($clients){

    }

}
