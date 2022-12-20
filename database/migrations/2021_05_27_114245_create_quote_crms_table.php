<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteCrmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_crms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('crm_id');
            $table->String('status');
            $table->String('name');
            $table->dateTime('date');
            $table->float('total');
            $table->dateTime('valid_till');
            $table->bigInteger('lead_id');
            $table->dateTime('last_update');
            $table->dateTime('date_of_decision');
            $table->bigInteger('invoice_id');
            $table->bigInteger('request_id');
            $table->boolean('is_sent');
            $table->longText('note');
            $table->longText('memo');
            $table->bigInteger('customers_quote');
            $table->bigInteger('connected_deal_id');
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
        Schema::dropIfExists('quote_crms');
    }
}
