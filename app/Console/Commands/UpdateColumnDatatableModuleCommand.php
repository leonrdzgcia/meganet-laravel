<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateColumnDatatableModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatecolumndatatablemodule:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiaza las columnas de las tablas';

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
        $modulesArray = [
        'Internet' => [
            'name' => 'Internet',
            'constant' => 'module.plan.internet.constants.'
        ],
        'Voise' => [
            'name' => 'Voise',
            'constant' => 'module.plan.voz.constants.'
        ],
        'Custom' => [
            'name' => 'Custom',
            'constant' => 'module.plan.custom.constants.'
        ],
        'Bundle' => [
            'name' => 'Bundle',
            'constant' => 'module.plan.paquete.constants.'
        ],
        'Crm' => [
            'name' => 'Crm',
            'constant' => 'module.crm.constants.'
        ],
        'DocumentCrm' => [
            'name' => 'DocumentCrm',
            'constant' => 'module.crm.constants.'
        ],
        'Client' => [
            'name' => 'Client',
            'constant' => 'module.client.constants.'
        ],
        'DocumentClient' => [
            'name' => 'DocumentClient',
            'constant' => 'module.client.constants.'
        ],
        'ClientInternetService' => [
            'name' => 'ClientInternetService',
            'constant' => 'module.client.service.constants.'
        ],
        'ClientVozService' => [
            'name' => 'ClientVozService',
            'constant' => 'module.client.service.constants.'
        ],
        'ClientCustomService' => [
            'name' => 'ClientCustomService',
            'constant' => 'module.client.service.constants.'
        ],
        'ClientBundleService' => [
            'name' => 'ClientBundleService',
            'constant' => 'module.client.service.constants.'
        ],
        'ClientPayment' => [
            'name' => 'ClientPayment',
            'constant' => 'module.client.billing.payment.constants.'
        ],
        'ClientTransaction' => [
            'name' => 'ClientTransaction',
            'constant' => 'module.client.billing.transaction.constants.'
        ],
        'Router' => [
            'name' => 'Router',
            'constant' => 'module.router.constants.'
        ],
        'Network' => [
            'name' => 'Network',
            'constant' => 'module.network.constants.'
        ],
        'NetworkIp' => [
            'name' => 'NetworkIp',
            'constant' => 'module.network.ipv4.constants.'
        ],
        'Partner' => [
            'name' => 'Partner',
            'constant' => 'module.administration.partner.constants.'
        ],
        'Location' => [
            'name' => 'Location',
            'constant' => 'module.administration.location.constants.'
        ],
        'State' => [
            'name' => 'State',
            'constant' => 'module.administration.state.constants.'
        ],
        'Municipality' => [
            'name' => 'Municipality',
            'constant' => 'module.administration.municipality.constants.'
        ],
        'Colony' => [
            'name' => 'Colony',
            'constant' => 'module.administration.colony.constants.'
        ],
        'Role' => [
            'name' => 'Role',
            'constant' => 'module.administration.rol.constants.'
        ],
        'Ticket' => [
            'name' => 'Ticket',
            'constant' => 'module.ticket.constants.'
        ],

    ];

        foreach ($modulesArray as $value) {
             $module = \App\Models\Module::where('name', $value['name'])->first();
            $module->columnsDatatable()->delete();
            $module->columnsDatatable()->insert($this->transformColumnsDatatableModel($value['constant'], $module));
        }
    }

    public function transformColumnsDatatableModel($dir, $module)
    {
        $values = [];
        $columns = config($dir . $module->name . '.DATATABLE_FIELDS');
        if (count($columns)) {
            foreach ($columns as $k => $v) {
                $values[] = [
                    'module_id' => $module->id,
                    'name' => $k,
                    'label' => $v['name'],
                    'class' => $v['class'],
                ];
            }
        }
        return $values;
    }
}
