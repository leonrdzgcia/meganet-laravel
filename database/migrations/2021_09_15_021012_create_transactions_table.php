<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->double('debit')->nullable();
            $table->double('credit')->nullable();
            $table->double('account_balance');
            $table->text('description')->nullable();
            $table->enum('category', ['Servicio', 'Pago']);
            $table->string('cantidad');
            $table->bigInteger('client_id');
            $table->enum('type', ['debit', 'credit']);
            $table->double('price');
            $table->integer('iva');
            $table->double('total');
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
            $table->text('comment')->nullable();
            $table->text('period')->nullable();
            $table->boolean('add_to_invoice');
            $table->double('company_balance');
            $table->string('movement');
            $table->string('service_name')->nullable();
            $table->string('invoice')->nullable();
            $table->bigInteger('transactionable_id');
            $table->string('transactionable_type');
            $table->boolean('is_payment')->default(false);
            $table->bigInteger('payment_id')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
