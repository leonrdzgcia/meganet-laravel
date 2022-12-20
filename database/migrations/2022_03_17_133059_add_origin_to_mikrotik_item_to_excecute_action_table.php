<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginToMikrotikItemToExcecuteActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mikrotik_item_to_excecute_actions', function (Blueprint $table) {
            $table->longText('origin')->nullable()->after('flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mikrotik_item_to_excecute_actions', function (Blueprint $table) {
            $table->dropColumn('origin');
        });
    }
}
