<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIpv4AssignmentInClientInternetService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = Module::where('name', 'ClientInternetService')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));

        $module = Module::where('name', 'ClientBundleService')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module = Module::where('name', 'ClientInternetService')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));

        $module = Module::where('name', 'ClientBundleService')->first();
        $module->fields()->delete();
        $module->fields()->insert($this->transformFieldsModel('module.client.service.constants.', $module));
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
