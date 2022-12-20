<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;
use App\Models\MethodOfPayment;

class PopulateMethodOfPaymentToFieldModule extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $bootstrap_multiselect = [1, 2];
        $select2 = [4, 5];
        $chosen_select = [21, 22];
        $toaster = [3];
        $datatables_packages = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
        $ckeditor = [23];
        $bootstrap_multiselect_toaster_datatables_packages = \Illuminate\Support\Arr::collapse([$bootstrap_multiselect, $toaster, $datatables_packages, $select2, $chosen_select, $ckeditor]);


        $module = Module::create(['name' => 'MethodOfPayment']);
        $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
        $module->fields()->insert($this->transformFieldsModel('module.administration.methodofpayment.constants.', $module));
        $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.administration.methodofpayment.constants.', $module));

        $module = Module::where('name', 'ClientPayment')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.payment.constants.', $module));

        $data = [
            ['type' => 'Efectivo en Caja','active' => 1],
            ['type' => 'Transferencia Bancaria','active' => 1],
            ['type' => 'Tarjeta de Credito o debito en Oficina','active' => 1],
            ['type' => 'PayPal','active' => 1],
            ['type' => 'Oxxo','active' => 1],
            ['type' => 'PAGO A TECNICO','active' => 1],
            ['type' => 'TARJETAS PREPAGO','active' => 1],
        ];
        MethodOfPayment::insert($data);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module = Module::where('name', 'MethodOfPayment')->first();
        $module->columnsDatatable()->delete();
        $module->packages()->detach();
        $module->fields()->delete();
        $module->delete();

        $module = Module::where('name', 'ClientPayment')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.billing.payment.constants.', $module));

        $module = MethodOfPayment::all()->first();
        $module->delete();
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
