<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateFieldByModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatefielmodule:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar campos de la base de datos dato el module';

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
        $this->updateAll();
        if (false) {
            //        $opcion = $this->ask("Actualizar todo(1) o un modulo simple (2) ");
//        if ($opcion == 1) {
//            $this->updateAll();
//            exit;
//        }

            $module = $this->ask('Dame el Module?');
            $constante = $this->ask('Dame direccion de la constante?');
            $opcion = $this->ask("Si ya existe (1), si no existe (2)");

            switch ($opcion) {
                case  1:
                    $this->update($module, $constante);
                    break;
                case  2:
                    $this->insert($module, $constante);
                    break;
            }
        }
    }

    public function insert($moduleName, $constante)
    {
        $module = \App\Models\Module::where(['name' => $moduleName])->first();
        $module->fields()->insert($this->transformFieldsModel($constante, $module));
    }

    public function update($moduleName, $constante)
    {
        $module = \App\Models\Module::where('name', $moduleName)->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel($constante, $module));
    }

    public function updateAll()
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
            'BundleLeft' => [
                'name' => 'BundleLeft',
                'constant' => 'module.plan.paquete.constants.'
            ],
            'BundleRight' => [
                'name' => 'BundleRight',
                'constant' => 'module.plan.paquete.constants.'
            ],
            'BundleBottom' => [
                'name' => 'BundleBottom',
                'constant' => 'module.plan.paquete.constants.'
            ],
            'Crm' => [
                'name' => 'Crm',
                'constant' => 'module.crm.constants.'
            ],
            'CrmMainInformation' => [
                'name' => 'CrmMainInformation',
                'constant' => 'module.crm.constants.'
            ],
            'CrmLeadInformation' => [
                'name' => 'CrmLeadInformation',
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
            'ClientMainInformation' => [
                'name' => 'ClientMainInformation',
                'constant' => 'module.client.constants.'
            ],
            'ClientAdditionalInformation' => [
                'name' => 'ClientAdditionalInformation',
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
            'ClientBillingConfigurationCustom' => [
                'name' => 'ClientBillingConfigurationCustom',
                'constant' => 'module.client.billing.view-billing.constants.'
            ],
            'ClientBillingConfigurationRecurrent' => [
                'name' => 'ClientBillingConfigurationRecurrent',
                'constant' => 'module.client.billing.view-billing.constants.'
            ],
            'ClientBillingAddress' => [
                'name' => 'ClientBillingAddress',
                'constant' => 'module.client.billing.view-billing.constants.'
            ],
            'ClientRemindersConfiguration' => [
                'name' => 'ClientRemindersConfiguration',
                'constant' => 'module.client.billing.view-billing.constants.'
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
            'RouterAdd' => [
                'name' => 'RouterAdd',
                'constant' => 'module.router.constants.'
            ],
            'Mikrotik' => [
                'name' => 'Mikrotik',
                'constant' => 'module.router.mikrotik.constants.'
            ],
            'MikrotikConfig' => [
                'name' => 'MikrotikConfig',
                'constant' => 'module.router.mikrotik.constants.'
            ],
            'NetworkEdit' => [
                'name' => 'NetworkEdit',
                'constant' => 'module.router.constants.'
            ],
            'Network' => [
                'name' => 'Network',
                'constant' => 'module.network.constants.'
            ],
            'Ipv4Calculator' => [
                'name' => 'Network',
                'constant' => 'module.network.ipv4.constants.'
            ],
            'NetworkIp' => [
                'name' => 'Network',
                'constant' => 'module.network.ipv4.constants.'
            ],
            'SettingDebtPaymentClientRecurrent' => [
                'name' => 'SettingDebtPaymentClientRecurrent',
                'constant' => 'module.setting.constants.'
            ],
            'Perfil' => [
                'name' => 'Perfil',
                'constant' => 'module.perfil.constants.'
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
            'Permission' => [
                'name' => 'Permission',
                'constant' => 'module.administration.permission.constants.'
            ],
            'TicketDetails' => [
                'name' => 'TicketDetails',
                'constant' => 'module.ticket.constants.'
            ],
            'Ticket' => [
                'name' => 'Ticket',
                'constant' => 'module.ticket.constants.'
            ],
            'TicketThread' => [
                'name' => 'TicketThread',
                'constant' => 'module.ticket.constants.'
            ],

        ];

        foreach ($modulesArray as $value) {
            $module = \App\Models\Module::where('name', $value['name'])->first();
            $module->fields()->delete();
            $module->fields()->insert($this->transformFieldsModel($value['constant'], $module));
        }

    }

    public function transformFieldsModel($dir, $module)
    {
        $fields = config($dir . $module->name . '.FIELDS');
        $id = $module->id;

        $values = [];
        if ($fields) {
            foreach ($fields as $k => $v) {
                $values[] = [
                    'name' => $k,
                    'type' => $v['type'] ?? 'depend-field',
                    'label' => $v['label'] ?? '',
                    'placeholder' => $v['placeholder'] ?? '',
                    'partition' => $v['partition'] ?? '',
                    'include' => isset($v['type']) && $v['type'] != 'depend-field' ? ($v['include'] ?? true) : false,
                    'hint' => $v['hint'] ?? '',
                    'search' => isset($v['search']) ? json_encode($v['search']) : null,
                    'options' => !isset($v['search']) ? json_encode($v['options'] ?? []) : null,
                    'inputGroup' => $v['inputGroup'] ?? '',
                    'inputGroupEnd' => $v['inputGroupEnd'] ?? '',
                    'depend' => $v['depend'] ?? null,
                    'inputs_depend' => $v['inputs_depend'] ?? null,
                    'value' => isset($v['value']) ? json_encode($v['value']) : null,
                    'default_value' => isset($v['default_value']) ? json_encode($v['default_value']) : null,
                    'disabled' => $v['disabled'] ?? false,
                    'position' => $v['position'] ?? 0,
                    'rule' => isset($v['rule']) ? json_encode($v['rule']) : null,
                    'module_id' => $id,
                    'step' => $v['step'] ?? null
                ];
            }
        }
        return $values;
    }
}
