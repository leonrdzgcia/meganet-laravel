<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->string('network')->nullable();
            $table->string('bm')->nullable();
            $table->string('rootnet')->nullable();
            $table->integer('used')->nullable();
            $table->string('title');
            $table->enum('network_type',['RootNet', 'EndNet' ])->nullable();
            $table->enum('network_category',['Dev', 'Coorporativa', 'Test', 'Produccion'])->nullable();
            $table->string('comment')->nullable();
            $table->string('location_id')->nullable();
            $table->enum('type_of_use',['Estatico', 'Pool'])->nullable();
            $table->boolean('allow_usage_network')->nullable();
            $table->integer('parent_id')->nullable();
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
        Schema::dropIfExists('networks');
    }
}
