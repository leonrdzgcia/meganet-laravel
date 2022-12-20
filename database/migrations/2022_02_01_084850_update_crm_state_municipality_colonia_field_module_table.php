<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCrmStateMunicipalityColoniaFieldModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('field_modules')->where('id',131)
        ->update(
            array(
                'name' => 'colony_id',
                'type' => 'select-2-estado-municipio-colonia-component',
                'label' => 'App\Models\CrmMainInformation',
                'placeholder' => 'Seleccionar Colonia',
                'position' => '16',
                'include' => true,
                'value' => null,
                'options' => [],
                'search' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'disabled' => false,
                'default_value' => null,
                'partition' => '',
                'rule' => null,
                'step' => null,
            )
        );
        DB::table('field_modules')->where('id',132)
        ->update(
            array(
                'name' => 'state_id',
                'type' => 'depend-field',
                'position' => '0',
                'include' => true,
                'value' => null,
                'options' => [],
                'search' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'disabled' => false,
                'default_value' => null,
                'partition' => '',
                'rule' => null,
                'step' => null,
            )
        );
        DB::table('field_modules')->where('id',133)
        ->update(
            array(
                'name' => 'municipality_id',
                'type' => 'depend-field',
                'position' => '0',
                'include' => true,
                'value' => null,
                'options' => [],
                'search' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'disabled' => false,
                'default_value' => null,
                'partition' => '',
                'rule' => null,
                'step' => null,
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('field_modules')->where('id',131)
        ->update(
            array(
                'name' => 'colony_id',
                'type' => 'select-2-estado-municipio-colonia-component',
                'label' => 'App\Models\CrmMainInformation',
                'placeholder' => 'Seleccionar Colonia',
                'position' => '16',
                'include' => true,
                'value' => null,
                'options' => [],
                'search' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'disabled' => false,
                'default_value' => null,
                'partition' => '',
                'rule' => null,
                'step' => null,
            )
        );
        DB::table('field_modules')->where('id',132)
        ->update(
            array(
                'name' => 'state_id',
                'type' => 'depend-field',
                'position' => '0',
                'include' => true,
                'value' => null,
                'options' => [],
                'search' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'disabled' => false,
                'default_value' => null,
                'partition' => '',
                'rule' => null,
                'step' => null,
            )
        );
        DB::table('field_modules')->where('id',133)
        ->update(
            array(
                'name' => 'municipality_id',
                'type' => 'depend-field',
                'position' => '0',
                'include' => true,
                'value' => null,
                'options' => [],
                'search' => null,
                'inputGroup' => '',
                'inputGroupEnd' => '',
                'depend' => null,
                'inputs_depend' => null,
                'disabled' => false,
                'default_value' => null,
                'partition' => '',
                'rule' => null,
                'step' => null,
            )
        );
    }
}
