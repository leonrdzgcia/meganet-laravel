<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPaymentServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_payment_services', function (Blueprint $table) {
            $table->id();
            $table->boolean('payment_in_time')->default(true)->comment('Campo que te dice si el pago se hizo en tiempo');
            $table->bigInteger('service_paymentable_id');
            $table->string('service_paymentable_type');
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
        Schema::dropIfExists('client_payment_services');
    }
}
