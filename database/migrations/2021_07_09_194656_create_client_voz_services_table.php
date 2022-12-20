<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientVozServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_voz_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('voz_id');
            $table->unsignedBigInteger('client_bundle_service_id')->nullable();
            $table->string('description');
            $table->bigInteger('amount');
            $table->string('unity');
            $table->bigInteger('price');
            $table->enum('pay_period',['Periodo 1','Periodo 2','Periodo 3','Periodo 4','Periodo 5']);
            $table->string('start_date');
            $table->string('finish_date')->nullable();

            $table->boolean('discount')->default(false);
            $table->integer('discount_percent')->nullable()->comment('if discount is true');
            $table->string('start_date_discount')->nullable()->comment('if discount is true');
            $table->string('end_date_discount')->nullable()->comment('if discount is true');
            $table->string('discount_message')->nullable()->comment('if discount is true');

            $table->string('estado');
            $table->string('password')->nullable();
            $table->string('voise_device')->nullable()->comment('Se debe crear una tabla con los dispositivos de voz');
            $table->string('direction')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('charged')->default(false);
            $table->boolean('deployed')->default(false);
            $table->timestamps();

            $table->foreign('voz_id')
                ->references('id')
                ->on('voises');

            $table->foreign('client_bundle_service_id')
                ->references('id')
                ->on('client_bundle_services')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_voz_services');
    }
}
