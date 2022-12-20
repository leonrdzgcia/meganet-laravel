<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\ClientGracePeriod;
use Illuminate\Console\Command;

class MakeTheClientInactiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maketheclientinactive:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Todos los clientes que esten bloqueados, que hayan sido desplegados y que hayan cobrado su primera vez que sean igual o sebrepasenn su tiempo de gracia pasaran a inactivos';

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
        $clients = Client::with('client_grace_period')
            ->whereHas('client_grace_period')
            ->leftJoin('client_grace_periods', 'clients.id', '=', 'client_grace_periods.client_id')
            ->leftJoin('billing_configurations', 'clients.id', '=', 'billing_configurations.client_id')

            ->where(function ($query) {
                $query->whereHas('internet_service', function ($query) {
                    $query->where('charged', 1)
                        ->where('deployed', 1);
                })
                    ->orWhere(function ($query) {
                        $query->whereHas('bundle_service', function ($query) {
                            $query->where('charged', 1)
                                ->where('deployed', 1);
                        });
                    });
            })
            ->whereRaw('DATE(client_grace_periods.created_at) = DATE(DATE_SUB(now(), INTERVAL billing_configurations.grace_period Day))')
            ->get();

        foreach ($clients as $client) {
            $client->client_main_information()->update(['estado' => 'Inactivo']);
            $client->client_grace_period()->delete();
        }
    }
}
