<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMikrotikTariffTargetTailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mikrotik_tariff_target_tails', function (Blueprint $table) {
            $table->id();
            $table->integer('mikrotik_tariff_main_tail_id');
            $table->integer('mikrotik_id');
            $table->integer('tariff_id');
            $table->integer('client_internet_service_id');
            $table->string('model');
            $table->longText('json');
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
        Schema::dropIfExists('mikrotik_tariff_target_tails');
    }
}
