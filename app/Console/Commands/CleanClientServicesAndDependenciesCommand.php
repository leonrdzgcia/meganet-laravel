<?php

namespace App\Console\Commands;

use App\Models\Balance;
use App\Models\ClientMainInformation;
use App\Models\NetworkIp;
use Illuminate\Console\Command;

class CleanClientServicesAndDependenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanclientservices:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todas las bases de datos que tienen que ver con los servicios y renova el cliente para ser desplegado';

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
        $models  = [
            'App\Models\ClientBundleService',
            'App\Models\ClientCustomService',
            'App\Models\ClientInternetService',
            'App\Models\ClientVozService',
            'App\Models\ClientGracePeriod',
            'App\Models\ClientInvoice',
            'App\Models\ClientPaymentService',
            'App\Models\MikrotikClientPpoe',
            'App\Models\MikrotikItemToExcecuteAction',
            'App\Models\MikrotikTariffMainTail',
            'App\Models\MikrotikTariffTargetTail',
            'App\Models\Payment',
            'App\Models\Receipt',
            'App\Models\ServiceInAddressList',
            'App\Models\Transaction',
        ];

        $this->clearClientsBalanceInDatabase();
        $this->clearClientsNetworkIpInDatabase();
        $this->delByModel($models);
        $this->updateNewClientInDatabase();
    }


public function clearClientsBalanceInDatabase()
{
    foreach (Balance::all() as $balance) {
        $balance->update(['amount' => '0']);
    }
    dump("balance hecho");
}

public function clearClientsNetworkIpInDatabase()
{
    foreach (NetworkIp::all() as $value) {
        $value->update(['used' => '0','used_by' => '--', 'client_id' => null]);
    }
    dump("IP hecho");
}

public function updateNewClientInDatabase()
{
    foreach (ClientMainInformation::all() as $value) {
        $value->update(['estado' => 'Nuevo' ], );
    }
    dump("client hecho");
}

public function delByModel($models){
    foreach ($models as $model){
        $model::getQuery()->delete();
        dump( $model.' clean');
    }
}
}
