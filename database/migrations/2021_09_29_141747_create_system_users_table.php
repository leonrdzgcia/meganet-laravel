<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('phone')->nullable();
            $table->bigInteger('partner_id');
            $table->string('timeout')->nullable();
            $table->string('last_ip')->nullable();
            $table->string('last_access')->nullable();
            $table->string('access_router_radius')->nullable();
            $table->boolean('send_name_in_mail');
            $table->boolean('cash_desk');
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
        Schema::dropIfExists('system_users');
    }
}
