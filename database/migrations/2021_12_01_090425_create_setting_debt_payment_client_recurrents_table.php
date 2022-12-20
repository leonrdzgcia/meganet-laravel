<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingDebtPaymentClientRecurrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_debt_payment_client_recurrents', function (Blueprint $table) {
            $table->id();
            $table->boolean('apply_discount');
            $table->unsignedInteger('percent_discount');
            $table->unsignedInteger('apply_group_of_days');
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
        Schema::dropIfExists('setting_debt_payment_client_recurrents');
    }
}
