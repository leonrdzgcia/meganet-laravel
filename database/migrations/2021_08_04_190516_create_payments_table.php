<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_method_id');
            $table->string('date');
            $table->double('amount');
            $table->string('payment_period');
            $table->text('comment')->nullable();
            $table->string('receipt')->unique()->comment('Se genera con la fecha más un número consecutivo generado.');
            $table->boolean('send_receipt_after_payment')->nullable();
            $table->bigInteger('add_by');
            $table->bigInteger('paymentable_id');
            $table->string('paymentable_type');
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
        Schema::dropIfExists('payments');
    }
}
