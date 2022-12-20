<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_modules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('module_id');
            $table->boolean('include')->default(true)->comment('false if not include in ComponentFormDefault');
            $table->string('name');
            $table->string('type');
            $table->string('label')->nullable();
            $table->string('hint')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('value')->nullable();
            $table->text('options')->nullable();
            $table->text('search')->nullable()->comment('if type select and you want get values by ajax request');
            $table->string('inputGroup',50)->nullable();
            $table->string('inputGroupEnd',50)->nullable();
            $table->string('depend',50)->nullable()->comment('if type select-component-with-input');
            $table->text('inputs_depend')->nullable();
            $table->bigInteger('position')->nullable();
            $table->boolean('disabled')->nullable();
            $table->string('default_value')->nullable();
            $table->string('partition')->nullable();
            $table->text('rule')->nullable();
            $table->string('step')->nullable();
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
        Schema::dropIfExists('field_modules');
    }
}
