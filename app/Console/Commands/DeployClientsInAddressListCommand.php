<?php

namespace App\Console\Commands;

use App\Models\ClientBundleService;
use Illuminate\Console\Command;
use App\Models\ServiceInAddressList;
use App\Jobs\Mikrotik\MikrotikCreateAddressList;
use App\Models\ClientInternetService;

class DeployClientsInAddressListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployclientsinaddresslist:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recorre la tabla service_in_address_list y monta en el mikrotik los servicios que no han sido montados';

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
        $lists = ServiceInAddressList::where('deployed','=',false)->get();
        $services = [];
        foreach( $lists as $list){
             if ($list->serviceable_type == 'App\Models\ClientInternetService'){
                 $services[] = ClientInternetService::find($list->serviceable_id);
             }

            if ($list->serviceable_type == 'App\Models\ClientBundleService'){
                foreach (ClientBundleService::find($list->serviceable_id)->service_internet()->get() as $service) {
                       $services[] = $service;
                }
            }
            $list->delete();
        }

        foreach( $services as $service){
                MikrotikCreateAddressList::dispatch($service);
        }
    }
}
