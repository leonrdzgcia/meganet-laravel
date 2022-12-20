<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_bundles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bundle_id');
            $table->bigInteger('plan_bundle_id');
            $table->bigInteger('cant');
            $table->string('plan_bundle_type');
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
        Schema::dropIfExists('plan_bundles');
    }
}
