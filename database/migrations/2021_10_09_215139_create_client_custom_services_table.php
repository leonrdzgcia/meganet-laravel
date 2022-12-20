<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCustomServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_custom_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('custom_id')->nullable();
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

            $table->string('payment_type')->nullable();
            $table->string('deferred_payment_in_month')->nullable();

            $table->string('estado');
            $table->boolean('charged')->default(false);
            $table->boolean('deployed')->default(false); 
            $table->timestamps();

            $table->foreign('custom_id')
                ->references('id')
                ->on('customs');

            $table->foreign('client_bundle_service_id')
                ->references('id')
                ->on('client_bundle_services')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_custom_services');
    }
}
