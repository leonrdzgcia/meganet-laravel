<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_configurations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');

            $table->bigInteger('type_billing_id');
            $table->bigInteger('payment_method_id')->nullable();
            $table->bigInteger('period')->unsigned()->nullable();
            $table->string('minimum_balance')->nullable();
            $table->boolean('send_financial_notification')->default(false);

            // if prepaid Custom or daily
            $table->boolean('create_monthly_invoice')->default(false);

            // if prepaid Recurrent
            $table->boolean('billing_activated')->default(false);
            $table->integer('billing_date')->nullable()->comment('Dia de facturacion');
            $table->integer('billing_expiration')->nullable()->comment('cantidad dias con servicio desde a partir del dia de facturacion');
            $table->integer('grace_period')->nullable()->comment('Cantidad de dias en los que se mantendra realizando facturacion del servicio aunque no haya saldo disponible');
            $table->integer('membership_percentage')->nullable();
            $table->boolean('create_invoice')->default(false)->comment('Create invoices (after Charge & Invoice)');
            $table->boolean('autopay_invoice')->default(false)->comment('Auto pay invoices from account balance');

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
        Schema::dropIfExists('billing_configurations');
    }
}
