<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmLeadInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_lead_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_id');
            $table->bigInteger('score')->nullable()->default(0);
            $table->string('last_contacted')->nullable();
            $table->string('instalation_date')->nullable()->unique();
            $table->unsignedBigInteger('crm_techical_user_id')->nullable();
            $table->string('crm_status');
            $table->bigInteger('owner_id')->comment('trabajador que recibió el cliente first time')->nullable();
            $table->string('source')->nullable()->comment('en source se escribe un pequeno comentario');
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
        Schema::dropIfExists('crm_lead_information');
    }
}
