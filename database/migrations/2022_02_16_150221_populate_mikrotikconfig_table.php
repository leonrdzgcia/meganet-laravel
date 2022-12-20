<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Router;

class PopulateMikrotikconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            $router = Router::find(1);
            $router->mikrotikconfig()->create([
                'meganet_config_ip_address' => '149.14.76.99',
                'custom_config_name_parent_router' => 'Meganet',
                'custom_config_comment_parent_router' => 'Parent',
                'custom_config_comment_sun_router' => 'MgNetSQS',
                'mikrotik_config_server_pppoe_name' => 'PPPoE_SERVER_VLAN_200',
                'mikrotik_config_server_pppoe_interface' => 'vlan200',
                'mikrotik_config_server_pppoe_mtu' => 1500,
                'mikrotik_config_server_pppoe_mru' => 1500,
                'mikrotik_config_server_pppoe_profile' => 'PPPOE_VLAN_200',
                'mikrotik_config_server_ppp_profile' => 'PPPOE_VLAN_200',
                'mikrotik_config_server_ppp_local_address' => '10.10.1.1',
                'mikrotik_config_server_ppp_remote_address' => 'estatica',
                'mikrotik_config_server_ppp_bridge' => 'RED_LOCAL_LAN'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\MikrotikConfig::find(1)->delete();
    }
}
