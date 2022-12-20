<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MethodOfPayment;

class PopulateMethodOfPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        MethodOfPayment::create(['type'=> 'Acuerdo de Pago', 'active' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        MethodOfPayment::where('type', 'Acuerdo de Pago')->first()->delete();
    }
}
