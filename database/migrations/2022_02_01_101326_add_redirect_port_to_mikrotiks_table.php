<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRedirectPortToMikrotiksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mikrotiks', function (Blueprint $table) {
            $table->string('port_redirect')->default(80)->after('url_redirect');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mikrotiks', function (Blueprint $table) {
            $table->dropColumn('port_redirect');
        });
    }
}
