<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClientBundleService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function modelName()
    {
        return 'ClientBundleService';
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function voz()
    {
        return $this->belongsTo('App\Models\Voise');
    }

    public function internet()
    {
        return $this->belongsTo('App\Models\Internet');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\Bundle');
    }

    public function service_internet()
    {
        return $this->hasMany(ClientInternetService::class);
    }

    public function service_voz()
    {
        return $this->hasMany(ClientVozService::class);
    }

    public function service_custom()
    {
        return $this->hasMany(ClientCustomService::class);
    }

    public function client_payment_service()
    {
        return $this->morphMany(ClientPaymentService::class, 'service_paymentable');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function client_serviceables()
    {
        return $this->morphToMany(ClientInvoice::class, 'client_serviceable')->withTimestamps();
    }

    public function client_grace_period()
    {
        return $this->morphOne(ClientGracePeriod::class, 'serviceable');
    }

    public function serviceHasIva()
    {
        return $this->bundle->tax_include;
    }

    public function service_in_address_list()
    {
        return $this->morphMany(ServiceInAddressList::class, 'serviceable');
    }

    public function getNewPriceByIva()
    {
        $tax = $this->bundle->tax;
        if ($tax) {
            $price = $this->bundle->price;
            return $price + ($price * $tax / 100);
        }
        return $this->bundle->price;
    }

    public function isDeployed()
    {
        return $this->deployed;
    }

    public function scopeFilters($query, $columns, $search = null)
    {
        if (isset($search)) {
            return $query->where(function ($query) use ($search, $columns) {
                foreach (collect($columns)->filter(function ($value) {
                    return $value != 'action';
                })->toArray() as $value) {
                    $query->orWhere($value, 'like', '%' . $search . '%');
                }
            });
        }
    }

    public function scopeBundleActive($query)
    {
        return $query->where('client_bundle_services.estado', '=', 'Activado');
    }

    public function scopeBundleCharged($query)
    {
         $query->where('client_bundle_services.charged', '=', 1);
    }

    public function scopeBundleDeployed($query)
    {
        $query->where('client_bundle_services.deployed', '=', 1);
    }

    public function scopeIsClientTypeOfBilling($query, $typeOfBilling)
    {
        $query->whereHas('client.client_main_information', function ($query) use ($typeOfBilling) {
            $query->where('type_of_billing_id', $typeOfBilling);
        });
    }

    public function scopeGetClientActiveBillingToday($query)
    {
        $query->whereHas('client.billing_configuration', function ($query) {
            $query->where('billing_activated', 1)
                ->where('billing_date', (integer)Carbon::now()->format('d'));
        });
    }

    public function scopeGetClientDontHaveClientPaymentToday($query)
    {
        $query->whereDoesntHave('client_payment_service', function ($query) {
            $query->whereDate('created_at', Carbon::now()->toDateString());
        });
    }

    public function scopeGetClientDontHaveTransactionToday($query)
    {
        $query->whereDoesntHave('transactions', function ($query) {
            $query->whereDate('created_at', Carbon::now()->toDateString());
        });
    }

    public function scopeGetClientDontHaveTransactionAMonthAgo($query)
    {
        $query->whereHas('transactions', function ($query) {
            $query->whereRaw('DATE(created_at) <= DATE(DATE_SUB(now(), INTERVAL billing_configurations.period MONTH))');
        });
    }
    public function scopeGetServicePaymentAMonthAgo($query)
    {
        $query->whereHas('client_payment_service', function ($query) {
            $query->whereRaw('DATE(created_at) <= DATE(DATE_SUB(now(),INTERVAL 1 MONTH))');
        });
    }

    public function scopeGetIsGracePeriodExpired($query){
        $query->orWhereHas('client.client_grace_period', function ($query) {
            $query->whereRaw('DATE(created_at) >= DATE(DATE_SUB(now(), INTERVAL billing_configurations.grace_period Day))');
        });
    }

    public function scopeGetIsClientEstado($query, $estado){
        $query->whereHas('client.client_main_information', function ($query) use ($estado){
            $query->where('estado', '=', $estado);
        });
    }

    public function scopePerteneceAClienteConBillingConfiguration($query){
        $query->whereHas('client.billing_configuration', function ($query){
            $query->where('billing_activated', '=', 1);
        });
    }

    public function scopeActivo($query){
        $query->where('estado', '=', 'Activado');
    }

    public function scopePendiente($query){
        $query->where('estado', '=', 'Pendiente');
    }

    public function scopeQueEsteEnUnPeriodoDeTiempoValido($query){
        $query->where('contract_start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
            ->where(function ($query) {
                $query->whereNull('contract_end_date')
                    ->orWhere('contract_end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'));
            });
    }

    public function scopeNoEstaCobrado($query)
    {
        return $query->where('charged', '=', 0);
    }

    public function scopeNoEsteDesplegado($query)
    {
        return $query->where('deployed', '=', 0);
    }

    public function scopeDesplegado($query)
    {
        return $query->where('deployed', '=', 1);
    }

    public function scopequeTengaServiciosDeInternetSinDesplegar($query){
        $query->whereHas('service_internet', function ($query) {
            $query->where('charged', '=', 0);
        });
    }

}
