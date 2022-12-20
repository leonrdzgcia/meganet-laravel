<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMikrotikConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mikrotik_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('router_id');
            $table->string('meganet_config_ip_address')->default('192.168.1.1')->comment('ip del sistema meganet');
            $table->string('custom_config_name_parent_router')->default('MgNetSQP');
            $table->string('custom_config_comment_parent_router')->default('Meganet');
            $table->string('custom_config_comment_sun_router')->default('MgNetSQS'); 

            $table->string('mikrotik_config_server_pppoe_name')->default('PPPoE_SERVER_VLAN_200');
            $table->string('mikrotik_config_server_pppoe_interface')->default('vlan200 Internet');
            $table->string('mikrotik_config_server_pppoe_mtu')->default('1500');
            $table->string('mikrotik_config_server_pppoe_mru')->default('1500');
            $table->string('mikrotik_config_server_pppoe_profile')->default('PPPOE_VLAN_200');

            $table->string('mikrotik_config_server_ppp_profile')->default('PPPOE_VLAN_200');
            $table->string('mikrotik_config_server_ppp_local_address')->default('10.10.0.1')->comment('Ip local de la interfaz');
            $table->string('mikrotik_config_server_ppp_remote_address')->default('estatica');
            $table->string('mikrotik_config_server_ppp_bridge')->default('RED_LOCAL_LAN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mikrotik_configs');
    }
}
