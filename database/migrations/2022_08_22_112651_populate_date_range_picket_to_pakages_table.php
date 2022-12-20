<?php

use App\Models\Module;
use App\Models\Package;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PopulateDateRangePicketToPakagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Package::insert([
            [
//                24
                'name' => 'date_range_picket',
                'url' => '/plugins/date-range-picker/date_range_picker.min.js',
                'type' => 'js'
            ],
            [
//                25
                'name' => 'date_range_picket',
                'url' => '/plugins/date-range-picker/date_range_picker.css',
                'type' => 'css'
            ],
        ]);

        $packages = Package::whereIn('name', ['date_range_picket', 'date_range_picket'])->get();
        $date_range_picker = collect($packages)->pluck('id')->toArray();
        $module = Module::where(['name' => 'Client'])->first();

        if ($module) {
            $module->packages()->attach($date_range_picker);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $array = [
            'date_range_picket',
            'date_range_picket'
        ];
        $packages = Package::whereIn('name', $array)->pluck('id')->toArray();
        $module = Module::where(['name' => 'Client'])->first();
        if ($module) {
           $module->packages()->detach($packages);
        }
    }
}
