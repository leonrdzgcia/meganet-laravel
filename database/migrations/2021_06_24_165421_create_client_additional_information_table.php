<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAdditionalInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_additional_information', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->nullable();
            $table->enum('category',['Particular','Empresa'])->nullable();
            $table->string('modem_sn')->nullable();
            $table->string('gpon_ont')->nullable();
            $table->float('power_dbm')->nullable();
            $table->string('original_password')->nullable();
            $table->string('vendor')->nullable();
            $table->string('box_nomenclator')->nullable();
            $table->string('user_film')->nullable();
            $table->string('password_film')->nullable();
            $table->string('password_wifi')->nullable();
            $table->string('reinstatement')->nullable();
            $table->string('social_id')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('installation_on_time')->nullable();
            $table->text('amount_technician_and_why')->nullable()->comment('El tecnico le atendio con amabilidad y respeto si, no y porque');
            $table->boolean('doubt_signed_contract')->nullable();
            $table->text('technician_attencion')->nullable()->comment('El tecnico le atendio con amabilidad y respeto si, no y porque');
            $table->string('last_time_online')->nullable();
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
        Schema::dropIfExists('client_additional_information');
    }
}
