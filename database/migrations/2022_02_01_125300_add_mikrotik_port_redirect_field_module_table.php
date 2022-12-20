<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FieldModule;

class AddMikrotikPortRedirectFieldModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $field_module = FieldModule::find(426);
        // $field_module->update(
        //     ['inputs_depend' => '{"url_redirect":{"field":"url_redirect","type":"input-string","value":null,"label":"Url de Redirecci\u00f3n","placeholder":"http:\/\/ejemplo.com","position":1},
        // "port_redirect":{"field":"port_redirect","type":"input-string","value":null,"label":"Puerto de redirecci\u00f3n","placeholder":"puerto","position":2},
        // "ip_redirect":{"field":"ip_redirect","type":"input-string","value":null,"label":"IP de redirecci\u00f3n","placeholder":"0.0.0.0","position":3},
        // "ips_with_comma_permited":{"field":"ips_with_comma_permited","type":"input-string","value":null,"label":"IPs","placeholder":"IPs de sitios permitidos separados por coma,sin espacios","position":1}}']
        // );

        // FieldModule::create([
        //     'name' => 'port_redirect',
        //     'type' => 'depend-field',
        //     'label' => '',
        //     'placeholder' => 'puerto',
        //     'partition' => '',
        //     'include' => false,
        //     'hint' => '',
        //     'search' => null,
        //     'options' => null,
        //     'inputGroup' => '',
        //     'inputGroupEnd' => '',
        //     'depend' => null,
        //     'inputs_depend' => null,
        //     'value' => null,
        //     'default_value' => null,
        //     'disabled' => false,
        //     'position' => 0,
        //     'rule' => null,
        //     'module_id' => 29,
        //     'step' => null
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $field_module = FieldModule::find(426);
        $field_module->update(
            [
        'inputs_depend' => '{"url_redirect":{"field":"url_redirect","type":"input-string","value":null,"label":"Url de Redirecci\u00f3n","placeholder":"http:\/\/ejemplo.com","position":1},
        "ip_redirect":{"field":"ip_redirect","type":"input-string","value":null,"label":"IP de redirecci\u00f3n","placeholder":"0.0.0.0","position":3},
        "ips_with_comma_permited":{"field":"ips_with_comma_permited","type":"input-string","value":null,"label":"IPs","placeholder":"IPs de sitios permitidos separados por coma,sin espacios","position":1}}'
            ]
        );
        FieldModule::where('name','port_redirect')->delete();
    }
}
