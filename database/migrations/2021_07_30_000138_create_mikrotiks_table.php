<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMikrotiksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mikrotiks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('router_id');
            $table->foreign('router_id')->references('id')->on('routers');
            $table->boolean('active')->default(false);
            $table->string('login_api')->nullable();
            $table->string('password_api')->nullable();
            $table->string('port_api')->nullable();
            $table->boolean('shaper_active')->default(false);
            $table->string('shaper')->nullable();
            $table->string('shaping_type')->nullable();
            $table->boolean('rule_wireless_access_list')->default(false);
            $table->string('url_redirect')->nullable()->comment('field depend on rule_wireless_access_list');
            $table->string('ip_redirect')->nullable()->comment('field depend on rule_wireless_access_list');
            $table->string('ips_with_comma_permited')->nullable()->comment('field depend on rule_wireless_access_list');
            $table->boolean('rule_address_list_mobility_client')->default(false);
            $table->boolean('bloking_rules')->default(false);
            $table->string('status')->nullable();
            $table->string('plataform')->nullable();
            $table->string('board_name')->nullable();
            $table->string('ros_version')->nullable();
            $table->string('cpu_load')->nullable();
            $table->string('ipv6')->nullable();
            $table->string('last_status')->nullable();
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
        Schema::dropIfExists('mikrotiks');
    }
}
