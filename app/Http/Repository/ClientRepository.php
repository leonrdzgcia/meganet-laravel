<?php

namespace App\Http\Repository;

use App\Http\Controllers\Utils\ReceiptController;
use App\Http\Requests\module\client\ClientDebitRectificationAgreementCreateRequest;
use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;
use App\Models\ClientInvoice;
use App\Models\ClientMainInformation;
use App\Models\MethodOfPayment;
use Carbon\Carbon;
use App\Models\TypeBilling;
use App\Models\ClientBundleService;
use App\Models\Client;
use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientRepository
{
    protected $client;
    protected $model;

    public function __construct()
    {
        $this->model = Client::query();
    }

    public function count()
    {
        return $this->model->count();
    }

    public function hasService($clientId, $service)
    {
        return $this->model->where('id', $clientId)->whereHas($service)->first();
    }

    public function canAddService($clientId, $service)
    {
        $client = Client::with('client_main_information')->find($clientId);

        if ($this->isRecurrente($client->client_main_information->type_of_billing_id)) {
            $clientBundleService = ClientBundleService::with('bundle.billings')
                ->where('client_id', $clientId)
                ->whereHas('bundle.billings', function ($query) {
                    $query->where('type', 'Pagos Recurrentes');
                })
                ->get();

            if ($service == 'bundle') {
                return true;
            }

            if ($clientBundleService->count() >= 1) {
                return true;
            }
        }

        if ($this->isPrepaid($client->client_main_information->type_of_billing_id)) {

            if ($service == 'bundle') {
                return false;
            }

            if (($service == 'internet') || ($service == 'voz') || ($service == 'custom')) {
                return true;
            }
        }
        return false;
    }

    public function getCostAllServiceActive($clientId)
    {
      // $client = $this->model->where('id', $clientId)->first();
        $client = $this->getServiceActive($clientId);
        $clientInternetService = $client->internet_service()
            ->whereNull('client_bundle_service_id')
            ->where('estado', 'Activado')
            ->sum('price');

        $clientVozService = $client->voz_service()
            ->whereNull('client_bundle_service_id')
            ->where('estado', 'Activado')
            ->sum('price');

        $clientCustomService = $client->custom_service()
            ->whereNull('client_bundle_service_id')
            ->where('estado', 'Activado')
            ->sum('price');

        $clientBundleService = $client->bundle_service()
            ->where('estado', 'Activado')
            ->sum('price');

        return $clientInternetService + $clientVozService + $clientCustomService + $clientBundleService;
    }

    public function getCostAllServiceSlope($clientId)
    {
        $client = $this->model->where('id', $clientId)->first();
        $clientInternetService = $client->internet_service()
            ->whereNull('client_bundle_service_id')
            ->where('estado', 'Pendiente')
            ->sum('price');

        $clientVozService = $client->voz_service()
            ->whereNull('client_bundle_service_id')
            ->where('estado', 'Pendiente')
            ->sum('price');

        $clientCustomService = $client->custom_service()
            ->whereNull('client_bundle_service_id')
            ->where('estado', 'Pendiente')
            ->sum('price');

        $clientBundleService = $client->bundle_service()
            ->where('estado', 'Pendiente')
            ->sum('price');

        return $clientInternetService + $clientVozService + $clientCustomService + $clientBundleService;
    }

    public function getActiveServiceExpiration($clientId)
    {
        $client = $this->model->where('id', $clientId)->first();

        if ($client) {
            $clientInternetService = $client->internet_service()
                ->where('estado', 'Activado')->first();

            if ($clientInternetService) {
                return  Carbon::createFromFormat('Y-m-d H:i:s', $clientInternetService->created_at)->addMonth($clientInternetService->period)->toDateTimeString();
            }
        }
        return false;
    }

    public function getActiveService($clientId)
    {
        $client = $this->model->where('id', $clientId)->first();
        $clientInternetService = $client->internet_service()
            ->where(function ($query) {
                $query->whereHas('bundle_service', function ($query) {
                    $query->where('estado', 'Activado');
                });
            })
            ->orWhere(function ($query) {
                $query->whereNull('client_bundle_service_id');
                $query->where('estado', 'Activado');
            })
            ->first();
        if ($clientInternetService) {
            return Carbon::parse($clientInternetService->start_date)->format('y-m-d');
        }
        return 0;
    }

    public function addDebitTransactionForPaymentService($model, $clientService, $newBalanceAndPrice, $invoise = null)
    {
        return $clientService->transactions()->create([
            'date' => Carbon::now()->toDateTimeString(),
            'debit' => $newBalanceAndPrice['cost'],
            'account_balance' => $newBalanceAndPrice['new_balance'],
            'description' => $clientService->description,
            'category' => 'Servicio',
            'cantidad' => '1',
            'client_id' => $clientService->client->id,
            'type' => 'debit',
            'price' => $newBalanceAndPrice['price'],
            'iva' => $model ? $clientService->$model->tax : 0,
            'total' => $newBalanceAndPrice['new_balance'],
            'from_date' => $this->getStartDate(),
            'to_date' => $this->getEndDate($clientService),
            'comment' => null,
            'add_to_invoice' => false,
            'company_balance' => $newBalanceAndPrice['new_balance'],
            'movement' => '+ ' . $newBalanceAndPrice['cost'],
            'service_name' => $clientService->description,
            'invoice' => $invoise->id ?? '',
            'is_payment' => false,
            'payment_id' => null,
        ]);
    }

    public function addDebitTransactionForPaymentDefaulter($client, $defaulterCost, $newBalance, $invoise = null)
    {
        return $client->transactions()->create([
            'date' => Carbon::now()->toDateTimeString(),
            'debit' => $defaulterCost,
            'account_balance' => $newBalance['new_balance'],
            'description' => 'Automatico',
            'category' => 'Servicio',
            'cantidad' => '1',
            'client_id' => $client->id,
            'type' => 'debit',
            'price' => $defaulterCost,
            'iva' => 0,
            'total' => $newBalance['new_balance'],
            'from_date' => null,
            'to_date' => null,
            'comment' => null,
            'add_to_invoice' => false,
            'company_balance' => $newBalance['new_balance'],
            'movement' => '+ ' . $defaulterCost,
            'service_name' => 'Automatico',
            'invoice' => $invoise->id,
            'is_payment' => false,
            'payment_id' => null,
        ]);
    }

    public function addDebitTransactionForPaymentAgreement($client, $defaulterCost, $newBalance, $invoise = null)
    {
        return $client->transactions()->create([
            'date' => Carbon::now()->toDateTimeString(),
            'debit' => $defaulterCost,
            'account_balance' => $newBalance['new_balance'],
            'description' => 'Rectificación de debito por Acuerdo de Pago',
            'category' => 'Servicio',
            'cantidad' => '1',
            'client_id' => $client->id,
            'type' => 'debit',
            'price' => $defaulterCost,
            'iva' => 0,
            'total' => 0,
            'from_date' => null,
            'to_date' => null,
            'comment' => null,
            'add_to_invoice' => false,
            'company_balance' => $newBalance['new_balance'],
            'movement' => '+ ' . $defaulterCost,
            'service_name' => 'Acuerdo de Pago',
            'invoice' => $invoise->id,
            'is_payment' => false,
            'payment_id' => null,
        ]);
    }

    public function getServiceActive($client_id)
    {
        $clientWithServices = Client::with([
            'bundle_service' => function ($query) {
                $query->where(function ($query) {
                    $query->where('estado', 'Activado');
                });
            }
            , 'internet_service' => function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('client_bundle_service_id');
                })
                    ->where(function ($query) {
                        $query->where('estado', 'Activado');
                    });
            }
            , 'voz_service' => function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('client_bundle_service_id');
                })
                    ->where(function ($query) {
                        $query->where('estado', 'Activado');
                    });
            }
            , 'custom_service' => function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('client_bundle_service_id');
                })
                    ->where(function ($query) {
                        $query->where('estado', 'Activado');
                    });
            }])
            ->where('id', $client_id)
            ->first();

        return $clientWithServices;
    }

    public function addInvoiceService($client_id, $isPayment)
    {
        $clientWithServices = $this->getServiceActive($client_id);
      //  $client = $this->model->where('id', $client_id)->first();

        $serviceExpiration = \Carbon\Carbon::parse($this->getActiveServiceExpiration($client_id))->format('Y-m-d');

        $clientInvoice = $clientWithServices->client_invoices()->create([
            'number' => $this->setInvoiceNumber(),
            'total' => $this->getCostAllServiceActive($client_id),
            'estado' => 'Pagar (del saldo de la cuenta)',
            'last_update' => Carbon::now()->toDateString(),
            'pay_up' => $serviceExpiration ?? null,
            'use_of_transactions' => 1,
            'payment' => $isPayment,
            'is_sent' => false,
            'delete_transactions' => false,
            'added_by' => '0',
            'type' => ClientInvoice::TYPE_INVOICE_SERVICES,
            'payment_date' => Carbon::now()->toDateString()
        ]);

        $services = [
            'bundle_service' => $clientWithServices['bundle_service'],
            'internet_service' => $clientWithServices['internet_service'],
            'voz_service' => $clientWithServices['voz_service'],
            'custom_service' => $clientWithServices['custom_service'],
        ];

        foreach ($services as $service) {
            if ($service->count()) {
                foreach ($service as $value) {
                    $value->client_serviceables()->attach(['client_invoice_id' => $clientInvoice->id]);
                }
            }
        }
        return $clientInvoice;
    }

    public function updateInvoiceService($clientInvoice, $isPayment)
    {
        return $clientInvoice->update([
            'payment' => $isPayment,
        ]);
    }

    public function addInvoiceDefaulter($client, $isPayment, $amount)
    {
        return $client->client_invoices()
            ->create([
                'number' => $this->setInvoiceNumber(),
                'total' => $amount,
                'estado' => $isPayment ? 'Pagado (Recargo Alquiler de dispositivo)' : 'Pagar (Recargo Alquiler de dispositivo)',
                'last_update' => Carbon::now()->toDateString(),
                'pay_up' => Carbon::now()->toDateString(),
                'use_of_transactions' => 1,
                'payment' => $isPayment,
                'is_sent' => false,
                'delete_transactions' => false,
                'added_by' => '0',
                'type' => ClientInvoice::TYPE_INVOICE_SURCHARGE_DEFAULTER,
                'payment_date' => Carbon::now()->toDateString()
            ]);
    }

    public function addInvoiceAgreement($client, $amount)
    {
        return $client->client_invoices()
            ->create([
                'number' => $this->setInvoiceNumber(),
                'total' => $amount,
                'estado' => 'Pagado',
                'last_update' => Carbon::now()->toDateString(),
                'pay_up' => Carbon::now()->toDateString(),
                'use_of_transactions' => 1,
                'payment' => true,
                'is_sent' => false,
                'delete_transactions' => false,
                'added_by' => '0',
                'type' => ClientInvoice::TYPE_INVOICE_AGREEMENT,
                'payment_date' => Carbon::now()->toDateString()
            ]);
    }

    public function updateInvoiceToCancel($invoicesWithoutPay, $inviceNumber)
    {

        foreach ($invoicesWithoutPay as $invoiceWithoutPay) {
            $invoiceWithoutPay->update([
                'estado' => 'Cancelada',
                'last_update' => Carbon::now()->toDateString(),
                'use_of_transactions' => 0,
                'payment' => false,
                'added_by' => '0',
                'note' => $inviceNumber,
            ]);
        }

    }

    public function setInvoiceNumber()
    {
        $count = ClientInvoice::count();
        if ($count) {
            $id = ClientInvoice::latest('id')->first()->id;
            return Carbon::now()->format('ym') . '000' . $id + 1;
        }
        return Carbon::now()->format('ym') . '000' . '1';
    }


    public function rectifyBalance($clientService, $newBalanceAndPrice)
    {
        $client = $this->model->find($clientService->client->id);
        $client->balance()->update(['amount' => $newBalanceAndPrice['new_balance']]);
    }


    public function updateBalance($client, $newBalance)
    {
        $client->balance()->update(['amount' => $newBalance]);
    }

    public function getStartDate()
    {
        return Carbon::now()->startOfDay()->format('Y-m-d\TH:i');
    }

    public function getEndDate($clientService)
    {
        $typeOfBilling = $clientService->client->client_main_information->type_of_billing_id;
        if ($typeOfBilling = TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT) {
            return Carbon::now()->addMonth($clientService->client->billing_configuration->period)->subDay()->endOfDay()->format('Y-m-d\TH:i');
        }

        if ($typeOfBilling = TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM) {
            return Carbon::now()->addMonth()->subDay()->endOfDay()->format('Y-m-d\TH:i');
        }

        if ($typeOfBilling = TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY) {
            return Carbon::now()->endOfDay()->format('Y-m-d\TH:i');
        }
    }

    public function isRecurrente($type_of_billing_id)
    {
        return $type_of_billing_id == TypeBilling::TYPE_OF_BILLING_PREPAID_RECURRENT;
    }

    public function isPrepaid($type_of_billing_id)
    {
        return $type_of_billing_id == TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM
            || $type_of_billing_id == TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY;
    }

    public function getBillingInforationBlock($clientId)
    {

        $client = Client::with('client_main_information','balance')->where('id', $clientId)->first();
        $clientTypeOfBilling = $client->client_main_information->type_of_billing_id;
        $costForMonth = $this->getCostAllServiceActive($clientId);
        $slopeCostForMonth = $this->getCostAllServiceSlope($clientId);
        $expirationDate = $this->getActiveServiceExpiration($clientId);
        $expirationDate = Carbon::createFromFormat('Y-m-d H:i:s', $expirationDate)->format('Y-m-d');
        $balance = $client->balance->amount;

        $daysLeft = $expirationDate ? Carbon::parse($expirationDate)->addDay()->diffInDays(Carbon::now()) : 0;
        $costPerDaysService = number_format($this->getCostAllServiceActive($clientId) / Carbon::now()->daysInMonth, 6);
        $daysLeft != 0 ? $costPerDaysServiceLeft = number_format($costPerDaysService * $daysLeft, 2) : $costPerDaysServiceLeft = 0;
        $array = [];


        if ($this->isPrepaid($clientTypeOfBilling)) {
            if (!$this->expirateAccount($expirationDate) && $expirationDate) {
                $array = [
                    'cost_for_month' => !$costForMonth ? $slopeCostForMonth : $costForMonth,
                    'cost_per_days_service' => $costPerDaysServiceLeft,
                    'expiration_date' => $expirationDate,
                    'days_left' => $daysLeft,
                    'expired' => false,
                    'isRecurrent' => false,
                    'balance' => $balance,
                ];
            } else {
                $array = [
                    'cost_for_month' => !$costForMonth ? $slopeCostForMonth : $costForMonth,
                    'cost_per_days_service' => '0',
                    'expiration_date' => $expirationDate,
                    'days_left' => '0',
                    'expired' => true,
                    'isRecurrent' => false,
                    'balance' => $balance,
                ];
            }
        } else {
            $array = [
                'cost_for_month' => !$costForMonth ? $slopeCostForMonth : $costForMonth,
                'cost_per_days_service' => '0',
                'expiration_date' => $expirationDate,
                'days_left' => '0',
                'expired' => $this->expirateAccount($expirationDate),
                'isRecurrent' => true,
                'balance' => $balance,
            ];
        }
        return $array;
    }

    public function expirateAccount($date)
    {
        return Carbon::now() > $date;
    }

    public function getClientDebitRectificationAgreement( $request, $clientId)
    {
        $input = $request->all();
        $paymentMethodId = $input['payment_method_id'];
        $porcent ='0.'.$input['apply_group_of_months'];

        $client = Client::with('balance')->find($clientId);
        $ClientBalance = $client->balance->amount * -1;
        $newAmount = $ClientBalance - ( $ClientBalance * $porcent );

        $invoiceWithoutPay = ClientInvoice::where('client_id', $clientId)
            ->where('payment', 0)
            ->where('estado', '!=', 'Cancelada')
            ->get();

        if ($invoiceWithoutPay){
            $newBalance['new_balance'] = 0;
            $initDate = \Carbon\Carbon::parse( $invoiceWithoutPay->first()->created_at);
            $initMonth = $initDate->format("F");

            $valuesToPayment = [
                'amount' => $newAmount,
                'payment_method_id' => $paymentMethodId,
                'payment_period' => $initMonth .' - '. Carbon::now()->format("F"),
                'comment' => 'Acuerdo de Pago'
            ];

            $newInvoiceAgreement = $this->addInvoiceAgreement($client, $newAmount);
            $this->addDebitTransactionForPaymentAgreement($client, $newAmount, $newBalance, $newInvoiceAgreement);
            $client->clientCreatePaymentAgreement($valuesToPayment);
            $this->updateInvoiceToCancel($invoiceWithoutPay, $newInvoiceAgreement->number);
            $client->balance()->update(['amount' => '0']);
        }

    }

    public function isClientBalanceSufficientRemoveClientFromAddressList($client)
    {
        if ($this->isClientBalanceSufficient($client)) {
            $internet_services = $client->internet_service()->get();
            if ($internet_services) {
                foreach ($internet_services as $clientService) {
                    MikrotikRemoveClientServiceFromAddressList::dispatch($clientService);
                }
            }
        }
    }
    public function isClientBalanceSufficient($client)
    {
        return $client->balance->amount > $this->getCostAllServiceActive($client->id);
    }

    public function getPaymentMethod($payment_method_id){
      return MethodOfPayment::find($payment_method_id);
    }
}
