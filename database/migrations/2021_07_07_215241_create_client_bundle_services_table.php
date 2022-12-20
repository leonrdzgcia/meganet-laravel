<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientBundleServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_bundle_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('bundle_id');
            $table->string('description');
            $table->bigInteger('price');
            $table->string('pay_period')->nullable();
            $table->string('estado');

            $table->boolean('discount')->default(false);
            $table->integer('discount_percent')->nullable()->comment('if discount is true');
            $table->string('start_date_discount')->nullable()->comment('if discount is true');
            $table->string('end_date_discount')->nullable()->comment('if discount is true');
            $table->string('discount_message')->nullable()->comment('if discount is true');

            $table->string('contract_start_date');
            $table->string('contract_end_date')->nullable();
            $table->boolean('automatic_renewal');
            $table->boolean('charged')->default(false);
            $table->boolean('deployed')->default(false);
            $table->timestamps();

            $table->foreign('bundle_id')
                ->references('id')
                ->on('bundles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_bundle_services');
    }
}
