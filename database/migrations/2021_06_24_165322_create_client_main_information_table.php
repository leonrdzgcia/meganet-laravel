<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMainInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_main_information', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->bigInteger('user_id')->nullable()->default(null)->comment('Usuario del sistema');
            $table->string('user')->comment('Mikrotik User');
            $table->string('password')->nullable()->comment('Mikrotik User Password');;
            $table->string('estado')->default('Nuevo')
                ->comment('Nuevo => Nuevo(Todavía no conectado),
                Activo => Activado, Inactivo => Inactivo(No puede utilizar los servicios),
                Bloqueado => Bloqueado
            ');
            $table->bigInteger('type_of_billing_id')->nullable();
            $table->string('ift')->nullable();
            $table->string('name');
            $table->string('father_last_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('nif_pasaport')->nullable()->comment('RFC/CURP');
            $table->bigInteger('partner_id')->nullable();
            $table->string('street')->nullable();
            $table->string('external_number')->nullable();
            $table->string('internal_number')->nullable();
            $table->string('zip')->nullable();
            $table->string('colony_id')->nullable();
            $table->string('municipality_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->bigInteger('location_id')->nullable();
            $table->string('geo_data')->nullable();
            $table->string('discharge_date')->nullable()->comment('fecha de alta');
            $table->string('activation_date')->nullable()->comment('fecha en la que se convirtio de crm a cliente');
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
        Schema::dropIfExists('client_main_information');
    }
}
