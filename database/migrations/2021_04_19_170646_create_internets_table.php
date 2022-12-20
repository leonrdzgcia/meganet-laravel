<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internets', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("service_name");
            $table->boolean("update_description")->nullable()->default(false);
            $table->biginteger("price");
            $table->boolean("update_service")->nullable()->default(false);
            $table->boolean("tax_include");
            $table->biginteger("tax");
            $table->biginteger("download_speed");
            $table->biginteger("upload_speed");
            $table->biginteger("guaranteed_speed_limit");
            $table->enum('priority', ["Baja", "Normal", "Alta"]);
            $table->biginteger("aggregation");
            $table->biginteger("burst");
            $table->bigInteger("burt_umbral")->nullable();
            $table->bigInteger("burt_time")->nullable();
            $table->biginteger("rates_to_change")->nullable();
            $table->enum('prepaid_period', ["Mensual","Diario"])->nullable();
            $table->enum('transaction_category', ["Servicio","Descuento","Pago","Reembolso","Corrección" ])->nullable();
            $table->biginteger('amount_days')->nullable();
            $table->boolean("available_when_register_by_social_network")->nullable()->default(false);
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
        Schema::dropIfExists('internets');
    }
}
