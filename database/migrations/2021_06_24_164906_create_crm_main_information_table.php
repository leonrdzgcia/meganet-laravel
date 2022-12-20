<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmMainInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_main_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_id');
            $table->string('ift')->nullable();
            $table->string('name');
            $table->string('father_last_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('email')->nullable();
            $table->boolean('email_is_required')->default(true);
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('nif_pasaport')->nullable()->comment('RFC/CURP');
            $table->bigInteger('partner_id')->nullable();
            $table->bigInteger('location_id')->nullable(); ////ubicación de un dispositovo o sector por rango de IP
            $table->string('high_date')->nullable()->comment('fecha de alta');
            $table->string('street')->nullable();
            $table->string('external_number')->nullable();
            $table->string('internal_number')->nullable();
            $table->string('zip')->nullable();
            $table->unsignedBigInteger('colony_id')->nullable();
            $table->unsignedBigInteger('municipality_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->timestamps();

            $table->foreign('crm_id')
                ->references('id')
                ->on('crms')
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
        Schema::dropIfExists('crm_main_information');
    }
}
