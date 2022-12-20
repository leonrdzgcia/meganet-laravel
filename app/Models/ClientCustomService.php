<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCustomService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function modelName(){
        return 'ClientCustomService';
    }

    public function custom(){
        return $this->belongsTo('App\Models\Custom');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function internet(){
        return $this->belongsTo('App\Models\Internet');
    }

    public function bundle_service(){
        return $this->belongsTo('App\Models\ClientBundleService','client_bundle_service_id');
    }

    public function router(){
        return $this->belongsTo('App\Models\Router');
    }

    public function mikrotik_tariff_target_tail()
    {
        return $this->hasMany(MikrotikTariffTargetTail::class);
    }

    public function serviceHasIva()
    {
       return $this->custom->tax_include;
    }

    public function client_payment_service()
    {
        return $this->morphMany(ClientPaymentService::class, 'service_paymentable');
    }

    public function client_invoice()
    {
        return $this->morphMany(ClientInvoice::class, 'serviceable');
    }

    public function isDeployed(){
        return $this->deployed;
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function client_serviceables()
    {
        return $this->morphToMany(ClientInvoice::class, 'client_serviceable' );
    }

    public function client_grace_period()
    {
        return $this->morphOne(ClientGracePeriod::class, 'serviceable');
    }

    public function getNewPriceByIva()
    {
        $tax = $this->custom->tax;
            if ($tax) {
                $price = $this->custom->price;
                return $price + ($price * $tax / 100);
            }
        return $this->custom->price;
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

    public function scopePerteneceAClienteConBillingConfiguration($query){
        $query->whereHas('client.billing_configuration', function ($query){
            $query->where('billing_activated', '=', 1);
        });
    }

    public function scopeServicioNoPerteneceAUnPaquete($query){
        $query->whereDoesntHave('bundle_service');
    }

    public function scopeActivo($query){
        $query->where('estado', '=', 'Activado');
    }

    public function scopeQueEsteEnUnPeriodoDeTiempoValido($query){
        $query->where('start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
            ->where(function ($query) {
                $query->whereNull('finish_date')
                    ->orWhere('finish_date', '>=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'));
            });
    }

    public function scopeNoEstaCobrado($query)
    {
        return $query->where('charged', '=', 0);
    }

    public function scopeNoEstaDesplegado($query)
    {
        return $query->where('deployed', '=', 0);
    }

    public function scopePendiente($query){
        $query->where('estado', '=', 'Pendiente');
    }

    public function scopeDesplegado($query)
    {
        return $query->where('deployed', '=', 1);
    }
}
