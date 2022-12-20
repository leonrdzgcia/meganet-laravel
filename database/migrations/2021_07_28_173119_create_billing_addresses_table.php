<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For payments recurrent
        Schema::create('billing_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');

            $table->string('billing_name')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_zip_code')->nullable();
            $table->string('billing_city')->nullable();
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
        Schema::dropIfExists('billing_addresses');
    }
}
