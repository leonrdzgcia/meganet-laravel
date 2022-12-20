<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ScriptToFixedMikrotikTariff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       \App\Models\MikrotikTariffTargetTail::whereDoesntHave('client_internet_service')->delete();
       \App\Models\MikrotikTariffMainTail::whereDoesntHave('mikrotik_tariff_target_tail')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\MikrotikTariffTargetTail::whereDoesntHave('client_internet_service')->delete();
        \App\Models\MikrotikTariffMainTail::whereDoesntHave('mikrotik_tariff_target_tail')->delete();
    }
}
