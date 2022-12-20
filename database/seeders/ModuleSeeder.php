<?php

namespace Database\Seeders;

use App\Models\Internet;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bootstrap_multiselect = [1, 2];
        $select2 = [4, 5];
        $chosen_select = [21, 22];
        $toaster = [3];
        $datatables_packages = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
        $ckeditor = [23];
        $apechart = [20];
        $bootstrap_multiselect_toaster_datatables_packages = Arr::collapse([$bootstrap_multiselect, $toaster, $datatables_packages, $select2, $chosen_select, $ckeditor]);

        // Plan
        $module = Module::create(['name' => 'Internet']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.plan.internet.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.plan.internet.constants.', $module));

        $module = Module::create(['name' => 'Voise']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.plan.voz.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.plan.voz.constants.', $module));

        $module = Module::create(['name' => 'Custom']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.plan.custom.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.plan.custom.constants.', $module));

        $module = Module::create(['name' => 'Bundle']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.plan.paquete.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.plan.paquete.constants.', $module));

        $module = Module::create(['name' => 'BundleLeft', 'is_main' => false, 'main' => 'App\Models\Bundle']);
        $module->fields()->insert($this->transformFieldsModel('module.plan.paquete.constants.', $module));
        $module = Module::create(['name' => 'BundleRight', 'is_main' => false, 'main' => 'App\Models\Bundle']);
        $module->fields()->insert($this->transformFieldsModel('module.plan.paquete.constants.', $module));
        $module = Module::create(['name' => 'BundleBottom', 'is_main' => false, 'main' => 'App\Models\Bundle']);
        $module->fields()->insert($this->transformFieldsModel('module.plan.paquete.constants.', $module));

        // Crm
        $module = Module::create(['name' => 'Crm']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.crm.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.crm.constants.', $module));
        $module = Module::create(['name' => 'CrmMainInformation']);
        $module->fields()->insert($this->transformFieldsModel('module.crm.constants.', $module));
        $module = Module::create(['name' => 'CrmLeadInformation']);
        $module->fields()->insert($this->transformFieldsModel('module.crm.constants.', $module));
        $module = Module::create(['name' => 'DocumentCrm']);
        $module->fields()->insert($this->transformFieldsModel('module.crm.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.crm.constants.', $module));

        //cliente
        $module = Module::create(['name' => 'Client']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->packages()->attach($apechart);
        $module->fields()->insert($this->transformFieldsModel('module.client.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.constants.', $module));
        $module = Module::create(['name' => 'ClientMainInformation']);
        $module->fields()->insert($this->transformFieldsModel('module.client.constants.', $module));
        $module = Module::create(['name' => 'ClientAdditionalInformation']);
        $module->fields()->insert($this->transformFieldsModel('module.client.constants.', $module));
        $module = Module::create(['name' => 'DocumentClient']);
        $module->fields()->insert($this->transformFieldsModel('module.client.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.constants.', $module));

        $module = Module::create(['name' => 'ClientBundleService']);
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.service.constants.', $module));

        $module = Module::create(['name' => 'ClientInternetService']);
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.service.constants.', $module));
        $module = Module::create(['name' => 'ClientVozService']);
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.service.constants.', $module));
        $module = Module::create(['name' => 'ClientCustomService']);
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.service.constants.', $module));
        $module = Module::create(['name' => 'ClientBundleService']);
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.service.constants.', $module));

        $module = Module::create(['name' => 'ClientBillingConfigurationCustom']);
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.view-billing.constants.', $module));
        $module = Module::create(['name' => 'ClientBillingConfigurationRecurrent']);
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.view-billing.constants.', $module));
        $module = Module::create(['name' => 'ClientBillingAddress']);
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.view-billing.constants.', $module));
        $module = Module::create(['name' => 'ClientRemindersConfiguration']);
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.view-billing.constants.', $module));

        $module = Module::create(['name' => 'ClientPayment', 'is_main' => false, 'main' => 'App\Models\Payment']);
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.payment.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.billing.payment.constants.', $module));

        $module = Module::create(['name' => 'ClientTransaction', 'is_main' => false, 'main' => 'App\Models\Transaction']);
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.transaction.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.client.billing.transaction.constants.', $module));


        //Hardware Routers
        $module = Module::create(['name' => 'RouterAdd', 'is_main' => false, 'main' => 'App\Models\Router']);
        $module->fields()->insert($this->transformFieldsModel('module.router.constants.', $module));
        $module = Module::create(['name' => 'Router']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.router.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.router.constants.', $module));

        $module = Module::create(['name' => 'Mikrotik']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.router.mikrotik.constants.', $module));

        $module = Module::create(['name' => 'MikrotikConfig']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);

        $module = Module::create(['name' => 'NetworkEdit', 'is_main' => false, 'main' => 'App\Models\Network']);
        $module->fields()->insert($this->transformFieldsModel('module.network.constants.', $module));
        $module = Module::create(['name' => 'Network']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.network.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.network.constants.', $module));
        $module = Module::create(['name' => 'Ipv4Calculator']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.network.ipv4.constants.', $module));

        $module = Module::create(['name' => 'NetworkIp']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.network.ipv4.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.network.ipv4.constants.', $module));


        //Configuracion
        $module = Module::create(['name' => 'SettingDebtPaymentClientRecurrent']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.setting.constants.', $module));

        //Administracion
        $module = Module::create(['name' => 'Perfil', 'is_main' => false, 'main' => 'App\Models\User']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.perfil.constants.', $module));

        //Partner
        $module = Module::create(['name' => 'Partner']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.partner.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.partner.constants.', $module));
        //Location
        $module = Module::create(['name' => 'Location']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.location.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.location.constants.', $module));

        //State
        $module = Module::create(['name' => 'State']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.state.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.state.constants.', $module));

        //Municipality
        $module = Module::create(['name' => 'Municipality']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.municipality.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.municipality.constants.', $module));

        //Colony
        $module = Module::create(['name' => 'Colony']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.colony.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.colony.constants.', $module));


        //Role
        $module = Module::create(['name' => 'Role', 'is_main' => false, 'main' => 'Spatie\Permission\Models\Role']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.rol.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.rol.constants.', $module));
        //Permisions in role
        $module = Module::create(['name' => 'Permission']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.permission.constants.', $module));


        $module = Module::create(['name' => 'TicketDetails', 'is_main' => false, 'main' => 'App\Models\Ticket']);
        $module->fields()->insert($this->transformFieldsModel('module.ticket.constants.', $module));
        $module = Module::create(['name' => 'Ticket']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.ticket.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.ticket.constants.', $module));

        $module = Module::create(['name' => 'TicketThread']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.ticket.constants.', $module));

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
