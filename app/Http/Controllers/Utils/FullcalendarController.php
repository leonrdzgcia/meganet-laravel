<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FullcalendarController extends Controller
{
    public function getBillingConfiguration(Request $request)
    {
        $events = [];
        $billingDatePost = $request->postData["billing_date"];
        $billingExpirationPost = $request->postData["billing_expiration"];
        $gracePeriodPost = $request->postData["grace_period"];
        if ($billingDatePost) {
            $billingDate = Carbon::createFromDate(Carbon::now()->year, Carbon::now()->month, $billingDatePost)
                ->toDateString();
            $events[] = [
                'title' => 'Dia de facturación',
                'start' => $billingDate,
                'end' => $billingDate,
                'color' => '#c7e4f3'
            ];
        }
        if ($billingExpirationPost) {
            $billingExpiration = Carbon::createFromDate(Carbon::now()->year, Carbon::now()->month, $billingDatePost)
                ->addDays($billingExpirationPost)->toDateString();
            $events[] = [
                'title' => 'Dia de Expiracion del servicio',
                'start' => $billingExpiration,
                'end' => $billingExpiration,
                'color' => '#f8f0c7'
            ];
        }
        if ($gracePeriodPost) {
            $gracePeriod = Carbon::createFromDate(Carbon::now()->year, Carbon::now()->month, $billingDatePost)
                ->addDays($gracePeriodPost)->toDateString();
            $events[] = [
                'title' => 'Fin del Periodo de Gracia',
                'start' => $gracePeriod,
                'end' => $gracePeriod,
                'color' => '#ecd0d0'
            ];
        }
        return $events;
    }
}
