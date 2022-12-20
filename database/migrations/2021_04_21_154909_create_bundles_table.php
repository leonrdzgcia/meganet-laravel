<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("service_description")->nullable();
            $table->biginteger("price")->nullable();
            $table->boolean("tax_include")->nullable();
            $table->biginteger("tax")->nullable();
            $table->enum('transaction_category', ["Servicio","Descuento","Pago","Reembolso","Corrección" ])->nullable();
            $table->biginteger('amount_days')->nullable();
            $table->string("activation_fee")->nullable();
            $table->enum('get_activation_fee_when', ['En facturación del primer servicio','Al crear el servicio'])->nullable();
            $table->boolean("emit_invoice")->nullable();
            $table->biginteger("contract_duration")->nullable();
            $table->boolean("automatic_renewal")->nullable();
            $table->boolean("auto_reactivate")->nullable();
            $table->string("cancellation_fee")->nullable();
            $table->string("prior_cancellation_fee")->nullable();
            $table->biginteger("discount_period")->nullable();
            $table->biginteger("discount_value")->nullable();
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
        Schema::dropIfExists('bundles');
    }
}
