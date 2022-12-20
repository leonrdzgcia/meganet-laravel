<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number')->comment('numero generardo del pago año+mes+numero');
            $table->float('total')->comment('total a pagar');
            $table->dateTime('payment_date')->nullable()->comment('fecha de pago');
            $table->string('estado')->default('Pagar (del saldo de la cuenta)');
            $table->foreignId('client_id');
            $table->dateTime('last_update')->comment('ultima actualiacion de la factura');
            $table->date('pay_up')->comment('fecha que asigna la configuracion del fin de la facturacion');
            $table->integer('use_of_transactions')->nullable()->comment('cantidad de transacciones que necesito para pagar la fafctura');
            $table->longText('note')->nullable();
            $table->longText('memo')->nullable();
            $table->integer('payment')->nullable()->comment('Numero consecutivo de pago');
            $table->boolean('is_sent')->default(false);
            $table->boolean('delete_transactions')->default(false);
            $table->foreignId('added_by')->comment('Quien generó la faactura');
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
        Schema::dropIfExists('client_invoices');
    }
}
