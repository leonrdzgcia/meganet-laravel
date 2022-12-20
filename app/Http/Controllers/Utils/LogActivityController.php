<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\ClientMainInformation;
use App\Models\Crm;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

const RELATION = [
    'Crm' => 'getLogActivitiesCrm'
];

class LogActivityController extends Controller
{
    public function getLogActivities(Request $request, $id){
        if (isset(RELATION[$request->module])) {
            $function = RELATION[$request->module];
            return $this->$function($id);
        }
        return null;
    }

    public function getLogActivitiesCrm($id){
        $crm = Crm::where('id', $id)->with('log_activities')->first();
        if ($crm) return $crm->log_activities;
        return [];
    }
}
