<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMikrotikItemToExcecuteActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mikrotik_item_to_excecute_actions', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nuleable();
            $table->string('place')->nuleable();
            $table->string('flag')->nuleable();
            $table->longText('value')->nuleable();
            $table->string('action')->nuleable();
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
        Schema::dropIfExists('mikrotik_item_to_excecute_actions');
    }
}
