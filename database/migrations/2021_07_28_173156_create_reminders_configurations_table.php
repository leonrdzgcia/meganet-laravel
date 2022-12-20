<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders_configurations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');

            $table->boolean('activate_reminders')->nullable()->default(false);
            $table->enum('type_of_message',['Mail','SMS','Mail + SMS'])->nullable();
            $table->integer('reminder_1_days')->nullable();
            $table->integer('reminder_2_days')->nullable();
            $table->integer('reminder_3_days')->nullable();
            $table->boolean('reminder_payment_3')->nullable();
            $table->double('reminder_payment_amount')->default(false)->nullable();
            $table->text('reminder_payment_comment')->nullable();

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
        Schema::dropIfExists('reminders_configurations');
    }
}
