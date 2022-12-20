<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientInternetServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_internet_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('internet_id')->nullable();
            $table->unsignedBigInteger('client_bundle_service_id')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('unity')->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('pay_period');
            $table->string('start_date')->nullable();
            $table->string('finish_date')->nullable();

            $table->boolean('discount')->default(false);
            $table->integer('discount_percent')->nullable()->comment('if discount is true');
            $table->string('start_date_discount')->nullable()->comment('if discount is true');
            $table->string('end_date_discount')->nullable()->comment('if discount is true');
            $table->string('discount_message')->nullable()->comment('if discount is true');

            $table->enum('estado', ['Activado', 'Desactivado', 'se detuvo', 'Pendiente', 'archivado']);
            $table->unsignedBigInteger('router_id')->nullable();
            $table->string('client_name')->nullable();
            $table->string('password')->nullable();

            $table->enum('ipv4_assignment', ['IP Estatica', 'Pool IP'])->nullable();
            $table->string('ipv4')->nullable()->comment('if ipv4_assignment is IP Estatica');
            $table->string('additional_ipv4')->nullable()->comment('if ipv4_assignment is IP Estatica');
            $table->integer('ipv4_pool')->nullable()->comment('if ipv4_assignment is Pool IP');

            $table->string('ipv6')->nullable();
            $table->string('delegated_ipv6')->nullable();
            $table->string('mac')->nullable();
            $table->string('portid')->nullable();

            $table->string('payment_type')->nullable();
            $table->string('deferred_payment_in_month')->nullable();
            $table->float('cost_activation')->default(0);

            $table->boolean('charged')->default(false);
            $table->boolean('deployed')->default(false);
            $table->timestamps();

            $table->foreign('internet_id')
                ->references('id')
                ->on('internets');

            $table->foreign('client_bundle_service_id')
                ->references('id')
                ->on('client_bundle_services')
                ->onDelete('cascade');;

            $table->foreign('router_id')
                ->references('id')
                ->on('routers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_internet_services');
    }
}
