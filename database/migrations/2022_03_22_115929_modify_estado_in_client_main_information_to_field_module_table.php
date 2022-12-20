<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FieldModule;

class ModifyEstadoInClientMainInformationToFieldModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $fieldModule = FieldModule::where('module_id',13)
        ->where('name','estado')->first();
        $fieldModule->update(
            [
                'options' =>'{"Nuevo":"Nuevo(Todav\u00eda no conectado)","Activo":"Activado","Inactivo":"Inactivo(No puede utilizar los servicios)","Bloqueado":"Bloqueado","Cancelado":"Cancelado"}'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $fieldModule = FieldModule::where('module_id',13)
        ->where('name','estado')->first();
        $fieldModule->update(
            [
                'options' =>'{"Nuevo":"Nuevo(Todav\u00eda no conectado)","Activo":"Activado","Inactivo":"Inactivo(No puede utilizar los servicios)","Bloqueado":"Bloqueado","Eliminado":"Eliminado"}'
            ]
        );
    }
}
