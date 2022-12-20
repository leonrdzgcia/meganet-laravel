<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Router;
use App\Http\Traits\RouterConnection;

class RemoveClientInMikrotikIfDoesntHaveClientServiceCommand extends Command
{
    use RouterConnection;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'removeinmikrotikwithoutclientservice:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina todos los clientes del Mikrotik que no tengan servicios en el sistema';

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
        $clientMikrotikNameArray = [];
        $clientsWithSerevices = Client::with('mikrotik_client_ppoe','internet_service','client_main_information')
        ->whereHas('internet_service')
        ->get();

        $clientsWithServiceNameArray = [];
        foreach($clientsWithSerevices as $clientsWithSerevice){
            $clientsWithServiceNameArray[] = $clientsWithSerevice->client_main_information->user;
        }

        $routers = Router::with('mikrotik')->where('type_of_nas','Mikrotik')->get();

        foreach($routers as $router){
            if ($router){
                $mikrotik = $router->mikrotik;
                    if ($mikrotik) {
                        $connected = $this->initConnection($mikrotik, $router->ip_host);
                        if ($connected) {
                            $clientMikrotiks = $this->getPpoeClients($connected);
                                foreach ($clientMikrotiks as $clientMikrotik){
                                    $clientMikrotikNameArray[] = $clientMikrotik['name'];
                                }
                        }
                        $rermoveThisClients = array_diff( $clientMikrotikNameArray, $clientsWithServiceNameArray);
                        foreach ($rermoveThisClients as $name ) {
                            $this->deleteClientePpoe( $connected, $name);
                        }
                    }
            }
        }
    }
}
