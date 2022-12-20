<?php

namespace App\Models;

use App\Http\Repository\ClientRepository;
use App\Http\Traits\Models\Client\ClientTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory, ClientTrait, Notifiable;

    protected $guarded = [];
    protected $appends = ['custom_days_left'];

    const SINGLE_RELATIONS = [
        'ClientMainInformation' => [
            'relation_name' => 'client_main_information',
            'relation_field' => 'client_id',
            'local_relation' => 'id'
        ],
        'ClientAdditionalInformation' => [
            'relation_name' => 'client_additional_information',
            'relation_field' => 'client_id',
            'local_relation' => 'id'
        ]
    ];

    private $clientRepository;

    public function user()
    {
        return $this->hasOne(ClientUser::class);
    }

    public function client_main_information()
    {
        return $this->hasOne(ClientMainInformation::class);
    }

    public function client_additional_information()
    {
        return $this->hasOne(ClientAdditionalInformation::class);
    }

    public function billing_configuration()
    {
        return $this->hasOne(BillingConfiguration::class, 'client_id');
    }

    public function reminder_configuration()
    {
        return $this->hasOne(RemindersConfiguration::class, 'client_id');
    }

    public function billing_address()
    {
        return $this->hasOne(BillingAddress::class, 'client_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function client_invoices()
    {
        return $this->hasMany(ClientInvoice::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function balance()
    {
        return $this->morphOne(Balance::class, 'balanceable');
    }

    public function receipt()
    {
        return $this->morphOne(Receipt::class, 'receiptable');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'customer_lead');
    }

    public function tickets_open()
    {
        return $this->hasMany(Ticket::class, 'customer_lead')->whereNotIn('estado', ['Cerrado', 'Reciclado']);
    }

    public function tickets_closed()
    {
        return $this->hasMany(Ticket::class, 'customer_lead')->whereIn('estado', ['Cerrado', 'Reciclado']);
    }

    public function internet_service()
    {
        return $this->hasMany(ClientInternetService::class);
    }

    public function voz_service()
    {
        return $this->hasMany(ClientVozService::class);
    }

    public function custom_service()
    {
        return $this->hasMany(ClientCustomService::class);
    }

    public function bundle_service()
    {
        return $this->hasMany(ClientBundleService::class);
    }

    public function documents()
    {
        return $this->hasMany(DocumentClient::class);
    }

    public function network_ip()
    {
        return $this->hasMany(NetworkIp::class);
    }

    public function mikrotik_client_ppoe()
    {
        return $this->hasOne(MikrotikClientPpoe::class);
    }

    public function client_grace_period()
    {
        return $this->hasOne(ClientGracePeriod::class);
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

    public function routeNotificationForMail()
    {
        return $this->client_main_information->email ?? '';
    }

    public function scopePromisePayment($query)
    {
        return $query->whereHas('payments', function ($query) {
            $query->where('payment_promise', true)
                ->where(function ($query) {
                    $query->whereDate('first_court_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                        ->WhereDate('third_court_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
                });
        });
    }

    public function scopeHaveServices($query)
    {
        return $query->whereHas('internet_service')
            ->orWhereHas('bundle_service');
    }

    public function getCustomDaysLeftAttribute($value)
    {
        $clientInternetService = $this->internet_service()
            ->where('estado', 'Activado')
            ->whereNull('client_bundle_service_id')
            ->first();

        if ($clientInternetService) {
            $expirationDate = Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->created_at)->addMonth($clientInternetService->period)->toDateTimeString();
            return Carbon::parse($expirationDate)->addDay()->diffInDays(Carbon::now());

        }
        return '-';
    }

    public function scopeTipoRecurrenteYElDiaDeFacturacionSeaHoy($query)
    {
        $query->where(function ($query) {
            $query->whereHas('client_main_information', function ($query) {
                $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
            })
                ->whereHas('billing_configuration', function ($query) {
                    $query->where('billing_date', (integer)Carbon::now()->format('d'));
                });
        });
    }

    public function scopeOSeaDeTipoCustomYTocaFacturarHoy($query)
    {
        $query->orWhere(function ($query) {
            $query
                ->whereHas('client_main_information', function ($query) {
                    $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
                })
                ->whereHas('payments', function ($query) {
                    $query->whereRaw('DATE(created_at) <= DATE(DATE_SUB(now(),INTERVAL 1 MONTH))');
                });
        });
    }

    public function scopeClienteDeTipoCustom($query){
        $query->whereHas('client_main_information', function ($query) {
            $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM);
        });
    }

    public function scopeClienteDeTipoRecurrente($query){
        $query->whereHas('client_main_information', function ($query) {
            $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
        });
    }

    public function scopeElClienteNoTieneFacturaCreadaEsteMes($query){
        $query->whereHas('client_invoices', function ($query) {
            $query->where(DB::raw('DATE_FORMAT(created_at,"%m")'), '<', Carbon::now()->format('m'));
        })
            ->orWhereDoesntHave('client_invoices');
    }

    public function scopeClienteDeTipoRecurrenteHoyDiaDeFacturacion($query){
        $query->whereHas('billing_configuration', function ($query) {
            $query->where('type_of_billing_id', TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT);
        });
    }

    public function LastInvoice(){
        return ClientInvoice::where(DB::raw('DATE_FORMAT(created_at,"%m")'), Carbon::now()->format('m'))->orderBy('id', 'desc')->first();
    }
}
