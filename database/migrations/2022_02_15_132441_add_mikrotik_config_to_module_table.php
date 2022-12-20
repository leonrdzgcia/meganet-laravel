<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;
use App\Models\FieldModule;

class AddMikrotikConfigToModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = Module::where('name', 'MikrotikConfig')->first();

        $fields =
        [
            [
                'name' => 'meganet_config_ip_address',
                'type' => 'input-string',
                'label' => 'IP/Host Meganet',
                'placeholder' => 'IP/Host Meganet',
                'partition' => '',
                'include' => true,
                'hint' => '',
                'search' => null,
                'options' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'value' => null,
                'default_value' => null,
                'disabled' => false,
                'position' => 1,
                'rule' => null,
                'module_id' => $module->id
            ],
            [
                'name' => 'custom_config_name_parent_router',
                'type' => 'input-string',
                'label' => 'Queue Nombre Hilo Padre',
                'placeholder' => 'Queue Nombre Hilo Padre',
                'partition' => '',
                'include' => true,
                'hint' => '',
                'search' => null,
                'options' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'value' => null,
                'default_value' => null,
                'disabled' => false,
                'position' => 2,
                'rule' => null,
                'module_id' => $module->id
            ],
            [
                'name' => 'custom_config_comment_parent_router',
                'type' => 'input-string',
                'label' => 'Queue comentario Hilo Padre',
                'placeholder' => 'Queue comentario Hilo Padre',
                'partition' => '',
                'include' => true,
                'hint' => '',
                'search' => null,
                'options' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'value' => null,
                'default_value' => null,
                'disabled' => false,
                'position' => 3,
                'rule' => null,
                'module_id' => $module->id
            ],
            [
                    'name' => 'custom_config_comment_sun_router',
                    'type' => 'input-string',
                    'label' => 'Queue comentario Hilo Hijo',
                    'placeholder' => 'Queue comentario Hilo Hijo',
                    'partition' => '',
                    'include' => true,
                    'hint' => '',
                    'search' => null,
                    'options' => null,
                    'inputGroup' => '',
                    'inputGroupEnd' => '',
                    'depend' => null,
                    'inputs_depend' => null,
                    'value' => null,
                    'default_value' => null,
                    'disabled' => false,
                    'position' => 4,
                    'rule' => null,
                    'module_id' => $module->id
            ],
            [
                    'name' => 'mikrotik_config_server_pppoe_name',
                    'type' => 'input-string',
                    'label' => 'Nombre del Servidor Ppoe',
                    'placeholder' => 'Nombre del Servidor Ppoe',
                    'partition' => '',
                    'include' => true,
                    'hint' => '',
                    'search' => null,
                    'options' => null,
                    'inputGroup' => '',
                    'inputGroupEnd' => '',
                    'depend' => null,
                    'inputs_depend' => null,
                    'value' => null,
                    'default_value' => null,
                    'disabled' => false,
                    'position' => 5,
                    'rule' => null,
                    'module_id' => $module->id
            ],
            [
                'name' => 'mikrotik_config_server_pppoe_interface',
                'type' => 'input-string',
                'label' => 'Nombre Interface para Servidor Ppoe',
                'placeholder' => 'Nombre Interface para Servidor Ppoe',
                'partition' => '',
                'include' => true,
                'hint' => '',
                'search' => null,
                'options' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'value' => null,
                'default_value' => null,
                'disabled' => false,
                'position' => 6,
                'rule' => null,
                'module_id' => $module->id
        ],
        [
            'name' => 'mikrotik_config_server_pppoe_mtu',
            'type' => 'input-string',
            'label' => 'Servidor Ppoe MTU',
            'placeholder' => 'Servidor Ppoe MTU',
            'partition' => '',
            'include' => true,
            'hint' => '',
            'search' => null,
            'options' => null,
            'inputGroup' => '',
            'inputGroupEnd' => '',
            'depend' => null,
            'inputs_depend' => null,
            'value' => null,
            'default_value' => null,
            'disabled' => false,
            'position' => 7,
            'rule' => null,
            'module_id' => $module->id
    ],
    [
        'name' => 'mikrotik_config_server_pppoe_mru',
        'type' => 'input-string',
        'label' => 'Servidor Ppoe MRU',
        'placeholder' => 'Servidor Ppoe MRU',
        'partition' => '',
        'include' => true,
        'hint' => '',
        'search' => null,
        'options' => null,
        'inputGroup' => '',
        'inputGroupEnd' => '',
        'depend' => null,
        'inputs_depend' => null,
        'value' => null,
        'default_value' => null,
        'disabled' => false,
        'position' => 8,
        'rule' => null,
        'module_id' => $module->id
],
[
    'name' => 'mikrotik_config_server_pppoe_profile',
    'type' => 'input-string',
    'label' => 'Servidor Ppoe Nombre del Perfil',
    'placeholder' => 'Servidor Ppoe Nombre del Perfil',
    'partition' => '',
    'include' => true,
    'hint' => '',
    'search' => null,
    'options' => null,
    'inputGroup' => '',
    'inputGroupEnd' => '',
    'depend' => null,
    'inputs_depend' => null,
    'value' => null,
    'default_value' => null,
    'disabled' => false,
    'position' => 9,
    'rule' => null,
    'module_id' => $module->id
],
[
    'name' => 'mikrotik_config_server_ppp_profile',
    'type' => 'input-string',
    'label' => 'Nombre Interface para Servidor Ppp',
    'placeholder' => 'Nombre Interface para Servidor Pppp',
    'partition' => '',
    'include' => true,
    'hint' => '',
    'search' => null,
    'options' => null,
    'inputGroup' => '',
    'inputGroupEnd' => '',
    'depend' => null,
    'inputs_depend' => null,
    'value' => null,
    'default_value' => null,
    'disabled' => false,
    'position' => 10,
    'rule' => null,
    'module_id' => $module->id
],
[
    'name' => 'mikrotik_config_server_ppp_local_address',
    'type' => 'input-string',
    'label' => 'Direccion local servidor Ppp',
    'placeholder' => 'Direccion local servidor Ppp',
    'partition' => '',
    'include' => true,
    'hint' => '',
    'search' => null,
    'options' => null,
    'inputGroup' => '',
    'inputGroupEnd' => '',
    'depend' => null,
    'inputs_depend' => null,
    'value' => null,
    'default_value' => null,
    'disabled' => false,
    'position' => 11,
    'rule' => null,
    'module_id' => $module->id
],
[
    'name' => 'mikrotik_config_server_ppp_remote_address',
    'type' => 'input-string',
    'label' => 'Direccion remota servidor Ppp',
    'placeholder' => 'Direccion remota servidor Ppp',
    'partition' => '',
    'include' => true,
    'hint' => '',
    'search' => null,
    'options' => null,
    'inputGroup' => '',
    'inputGroupEnd' => '',
    'depend' => null,
    'inputs_depend' => null,
    'value' => null,
    'default_value' => null,
    'disabled' => false,
    'position' => 12,
    'rule' => null,
    'module_id' => $module->id
],
[
    'name' => 'mikrotik_config_server_ppp_bridge',
    'type' => 'input-string',
    'label' => 'Nombre de la Interfaz puente',
    'placeholder' => 'Nombre de la Interfaz puente',
    'partition' => '',
    'include' => true,
    'hint' => '',
    'search' => null,
    'options' => null,
    'inputGroup' => '',
    'inputGroupEnd' => '',
    'depend' => null,
    'inputs_depend' => null,
    'value' => null,
    'default_value' => null,
    'disabled' => false,
    'position' => 13,
    'rule' => null,
    'module_id' => $module->id
],

        ];
        $module->fields()->insert($fields);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        FieldModule::where('name','meganet_config_ip_address')->delete();
        FieldModule::where('name','custom_config_name_parent_router')->delete();
        FieldModule::where('name','custom_config_comment_parent_router')->delete();
        FieldModule::where('name','custom_config_comment_sun_router')->delete();
        FieldModule::where('name','mikrotik_config_server_pppoe_name')->delete();
        FieldModule::where('name','mikrotik_config_server_pppoe_interface')->delete();
        FieldModule::where('name','mikrotik_config_server_pppoe_mtu')->delete();
        FieldModule::where('name','mikrotik_config_server_pppoe_mru')->delete();
        FieldModule::where('name','mikrotik_config_server_pppoe_profile')->delete();
        FieldModule::where('name','mikrotik_config_server_ppp_profile')->delete();
        FieldModule::where('name','mikrotik_config_server_ppp_local_address')->delete();
        FieldModule::where('name','mikrotik_config_server_ppp_remote_address')->delete();
        FieldModule::where('name','mikrotik_config_server_ppp_bridge')->delete();
    }
}
