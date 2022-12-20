<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->enum('type_of_nas',['Mikrotik','Cisco','Ubiquiti'])->nullable();
            $table->string('vendor_model')->nullable();
            $table->bigInteger('location_id')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('ip_host')->nullable();
            $table->string('nas_ip')->nullable();
            $table->string('secret_radius')->nullable();
            $table->string('pool')->nullable();
            $table->enum('authorization_accounting',[
                'PPP(Secrets)/API Acounting',
                'Hostpot(Users)/API accounting',
                'Hostopt(Radius)/Radius'
            ])->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('routers');
    }
}
