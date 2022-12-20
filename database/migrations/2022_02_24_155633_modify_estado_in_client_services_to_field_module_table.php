<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\FieldModule;
class ModifyEstadoInClientServicesToFieldModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $fieldModule = FieldModule::where('module_id',17)
        ->where('name','estado')->first();
        $fieldModule->update(['options' =>'{"Activado":"Activado","Desactivado":"Desactivado","Se detuvo":"Se detuvo", "Pendiente":"Pendiente", "Archivado":"Archivado"}',"default_value" => '"Pendiente"']);

        $fieldModule = FieldModule::where('module_id',16)
        ->where('name','bundle_estado')->first();
        $fieldModule->update(['options' =>'{"Activado":"Activado","Desactivado":"Desactivado","Se detuvo":"Se detuvo", "Pendiente":"Pendiente", "Archivado":"Archivado"}',"default_value" => '"Pendiente"' ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $fieldModule = FieldModule::where('module_id',17)
        ->where('name','estado')->first();
        $fieldModule->update(['options' =>'{"Activado":"Activado","Desactivado":"Desactivado"}']);

        $fieldModule = FieldModule::where('module_id',16)
        ->where('name','bundle_estado')->first();
        $fieldModule->update(['options' =>'{"Activado":"Activado","Desactivado":"Desactivado"}']);
    }
}
