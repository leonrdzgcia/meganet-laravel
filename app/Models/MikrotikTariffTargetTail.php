<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MikrotikTariffTargetTail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function client_internet_service(){
        return $this->belongsTo('App\Models\ClientInternetService');
    }

    public function mikrotik_tariff_main_tail(){
        return $this->belongsTo('App\Models\MikrotikTariffMainTail');
    }

    
}
