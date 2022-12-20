<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInternetService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function modelName(){
        return 'ClientInternetService';
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

    public function network_ip()
    {
        return $this->belongsTo('App\Models\NetworkIp','ipv4');
    }

    public function mikrotik_tariff_target_tail()
    {
        return $this->hasOne(MikrotikTariffTargetTail::class);
    }

    public function client_payment_service()
    {
        return $this->morphMany(ClientPaymentService::class, 'service_paymentable');
    }

    public function client_invoice()
    {
        return $this->morphMany(ClientInvoice::class, 'serviceable');
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

    public function service_in_address_list()
    {
        return $this->morphMany(ServiceInAddressList::class, 'serviceable');
    }


    public function serviceHasIva()
    {
       return $this->internet->tax_include;
    }

    public function getNewPriceByIva()
    {
        $tax = $this->internet->tax;
            if ($tax) {
                $price = $this->internet->price;
                return $price + ($price * $tax / 100);
            }
        return $this->internet->price;
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

    public function isDeployed(){
        return $this->deployed;
    }

    public function getIp(){
        return $this->ipv4;
    }
    public function scopeIsClientTypeOfBilling($query, $typeOfBilling)
    {
        $query->whereHas('client.client_main_information', function ($query) use ($typeOfBilling) {
            $query->where('type_of_billing_id', $typeOfBilling);
        });
    }

    public function scopeInternetActive($query)
    {
        return $query->where('estado', '=', 'Activado');
    }

    public function scopeInternetCharged($query)
    {
        return $query->where('charged', '=', 1);
    }

    public function scopeDesplegado($query)
    {
        return $query->where('deployed', '=', 1);
    }

    public function scopeNoEsteDesplegado($query)
    {
        return $query->where('deployed', '=', 0);
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
            $query->whereRaw('DATE(created_at) = DATE(DATE_SUB(now(), INTERVAL billing_configurations.period MONTH))');
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

    public function scopeTengaMikrotikAsignado($query){
        $query->whereHas('router.mikrotik');
    }

    public function scopeEsteEnElAddressList($query){
        $query->whereHas('service_in_address_list');
    }

    public function scopeNoEsteEnElAddressList($query){
        $query->whereDoesntHave('service_in_address_list');
    }

    public function scopeServicioNoPerteneceAUnPaquete($query){
        $query->whereDoesntHave('bundle_service');
    }

    public function scopePendiente($query){
        $query->where('estado', '=', 'Pendiente');
    }

    public function scopeActivo($query){
        $query->where('estado', '=', 'Activado');
    }

    public function scopeNoEstaCobrado($query){
        $query->where('charged', '=', 0);
    }

    public function scopeQueEsteEnUnPeriodoDeTiempoValido($query){
        $query->where('start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
            ->where(function ($query) {
                $query->whereNull('finish_date')
                    ->orWhere('finish_date', '>=', \Carbon\Carbon::now()->format('Y-m-d\TH:i'));
            });
    }

}

