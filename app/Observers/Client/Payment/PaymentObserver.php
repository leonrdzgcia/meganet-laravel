<?php

namespace App\Observers\Client\Payment;

use App\Jobs\Mikrotik\MikrotikCreateAddressList;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Jobs\DeletedClientWithServiceJob;
use App\Models\Client;
use App\Models\TypeBilling;
use App\Http\Repository\ClientRepository;
use Carbon\Carbon;
use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;

const PAYMENTTABLE_TYPE = [
    'App\Models\Client' => 'App\Jobs\Client\Payment\PaymentClientJob',
];

class PaymentObserver
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Handle the Mikrotik "created" event.
     *
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function created(Payment $payment)
    {
            if (isset(PAYMENTTABLE_TYPE[$payment->paymentable_type])) {
                $job = PAYMENTTABLE_TYPE[$payment->paymentable_type];
                $job::dispatchAfterResponse($payment, 'created');
        }
    }

    /**
     * Handle the Mikrotik "updated" event.
     *
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function updating(Payment $payment)
    {
        if (isset(PAYMENTTABLE_TYPE[$payment->paymentable_type])) {
            $oldPayment = Payment::findOrFail($payment->id);

            $job = PAYMENTTABLE_TYPE[$payment->paymentable_type];
            $job::dispatchAfterResponse($payment, 'updated', $oldPayment);
        }
    }

    /**
     * Handle the Mikrotik "deleted" event.
     *
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        if (isset(PAYMENTTABLE_TYPE[$payment->paymentable_type])) {
            $job = PAYMENTTABLE_TYPE[$payment->paymentable_type];
            $job::dispatchAfterResponse($payment, 'deleted');
        }
    }

    /**
     * Handle the Payment "deleted" event.
     *
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function deleting(Payment $payment)
    {
        if ($payment->isModelClient()) {
            $client = Client::find($payment->paymentable_id);
            $clientInternetService = $client->internet_service()->first();
            MikrotikCreateAddressList::dispatch($clientInternetService);
            $client->client_main_information->update(['estado' => 'Bloqueado']);
        }
    }

    public function clientNotRecurrent($client)
    {
        $type = $client->client_main_information()->first()->type_of_billing_id;
        return $type == TypeBilling::TYPE_OF_BILLING_PREPAID_DAILY ||
            $type == TypeBilling::TYPE_OF_BILLING_PREPAID_CUSTOM;
    }

    public function excededDatePayment($lastPaymentDate, $days)
    {
        return Carbon::parse(Carbon::now()->subDay())->diffInDays(
                $lastPaymentDate
            ) >= $days;
    }

}
