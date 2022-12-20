<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_crms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('crm_id');
            $table->bigInteger('total');
            $table->string('created');
            $table->string('expected_close');
            $table->enum('type',["algo", "algo1", "algo2"]);
            $table->bigInteger('owner');
            $table->bigInteger('lead_id');
            $table->string('last_update_at');
            $table->string('last_update_by');
            $table->string('source');
            $table->bigInteger('connected_quote_id');
            $table->string('customers_deal');
            $table->enum('status',["open", "won", "lost", "total"]);
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
        Schema::dropIfExists('deal_crms');
    }
}
