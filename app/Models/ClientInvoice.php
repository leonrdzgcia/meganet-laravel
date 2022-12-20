<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoice extends Model
{
    use HasFactory;
    protected $guarded = [];

    const TYPE_INVOICE_SERVICES = 1;
    const TYPE_INVOICE_SURCHARGE_DEFAULTER = 2;
    const TYPE_INVOICE_AGREEMENT = 3;


    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function client_bundle_service()
    {
        return $this->morphedByMany('App\Models\ClientBundleService', 'client_serviceable');
    }

    public function client_internet_service()
    {
        return $this->morphedByMany('App\Models\ClientBundleService', 'client_serviceable');
    }

    public function client_voz_service()
    {
        return $this->morphedByMany('App\Models\ClientBundleService', 'client_serviceable');
    }

    public function client_custom_service()
    {
        return $this->morphedByMany('App\Models\ClientBundleService', 'client_serviceable');
    }

    public function client_invoice_service()
    {
        return $this->hasMany('App\Models\ClientInvoiceService', 'client_invoice_id');
    }

}
