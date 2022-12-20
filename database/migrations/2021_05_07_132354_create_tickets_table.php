<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('topic')->nullable();
            $table->integer('customer_lead')->nullable()->comment('Este es el client_id');
            $table->integer('priority')->nullable();
            $table->enum('estado', ['Nuevo', 'Trabajo en curso', 'Resuelto', 'Esperando al cliente', 'Esperando al agente','Cerrado','Reciclado'])->default('Nuevo');
            $table->enum('group', ['Cualquier', 'IT', 'Finanzas', 'Ventas'])->nullable();
            $table->enum('type', ['Pregunta', 'Incidente', 'Problema', 'Solicitud de función', 'Cliente potencial'])->nullable();
            $table->integer('assigned_to')->nullable();
            $table->string('reporter')->nullable();
            $table->integer('reporter_id')->nullable();
            $table->string('reporter_type')->nullable();
            $table->integer('incoming_customer')->nullable();
            $table->boolean('hidden')->nullable();
            $table->string('task')->nullable();
            $table->string('star')->nullable();
            $table->string('source')->nullable();
            $table->string('trash')->nullable();
            $table->string('shareable')->nullable();
            $table->string('duplicate')->nullable();
            $table->boolean('disable_association_closed_status')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('date_time')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->unsignedBigInteger('colony_id')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
