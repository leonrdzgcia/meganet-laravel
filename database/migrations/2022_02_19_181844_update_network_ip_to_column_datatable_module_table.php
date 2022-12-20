<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNetworkIpToColumnDatatableModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
   DB::table('column_datatable_modules')
   ->where('module_id',34)
   ->where('name','used_by')
        ->update(
            array(
                'name' => 'client_name',
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
        DB::table('column_datatable_modules')
        ->where('module_id',34)
        ->where('name','client_name')
             ->update(
                 array(
                     'name' => 'used_by',
                 )
             );
    }
}
