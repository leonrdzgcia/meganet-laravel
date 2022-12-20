<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voises', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("service_name");
            $table->boolean("update_description")->default(false)->nullable();
            $table->biginteger("price");
            $table->boolean("update_service")->default(false)->nullable();
            $table->enum("type", ['VoIP', 'Corregido','Móvil']);
            $table->biginteger("partners")->nullable();
            $table->boolean("tax_include");
            $table->biginteger("tax");
            $table->enum('prepaid_period', ['Mensual','Diario'])->nullable();
            $table->bigInteger('rates_to_change')->nullable();
            $table->enum('transaction_category', ["Servicio","Descuento","Pago","Reembolso","Corrección"])->nullable();
            $table->enum('transaction_category_for_calls', ["Servicio","Descuento","Pago","Reembolso","Corrección"])->nullable();
            $table->enum('transaction_category_for_messages', ["Servicio","Descuento","Pago","Reembolso","Corrección"])->nullable();
            $table->enum('transaction_category_for_data', ["Servicio","Descuento","Pago","Reembolso","Corrección"])->nullable();
            $table->boolean('available_in_self_registration')->nullable();
            $table->string('bandwidth')->nullable();
            $table->enum('priority', ["1","2","3","4","5","6"]);
            $table->biginteger('amount_days')->nullable();
            $table->float('cost_activation')->default(0);
            $table->float('cost_instalation')->default(0);
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
        Schema::dropIfExists('voises');
    }
}
