<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->integer('network_id');
            $table->boolean('used')->default(0);
            $table->string('used_by')->nullable();
            $table->string('title')->nullable();
            $table->string('hostname')->nullable();
            $table->integer('location_id')->nullable();
            $table->string('host_category')->nullable();
            $table->string('ping')->nullable();
            $table->string('comment')->nullable();
            $table->integer('client_id')->nullable();
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
        Schema::dropIfExists('network_ips');
    }
}
