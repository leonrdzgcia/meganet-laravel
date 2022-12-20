<?php

namespace App\Jobs\Client\Payment;

use App\Http\Controllers\Utils\ReceiptController;
use App\Http\Repository\ClientRepository;
use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;
use App\Models\Client;
use App\Models\ClientInvoice;
use App\Models\ClientInvoiceService;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentClientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;
    protected $oldPayment;
    protected $action;
    protected $clientRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Payment $payment, $action, $oldPayment = null)
    {
        $this->payment = $payment;
        $this->action = $action;
        $this->oldPayment = $oldPayment;
    }

    public function handle(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $action = $this->action;
        $this->$action();
    }

    public function created()
    {
        $client = Client::findOrFail($this->payment->paymentable_id);
        $balance = $client->balance()->first();
        $balance->amount += $this->payment->amount;
        if ($this->payment->payment_method()->first()->type == 'Acuerdo de Pago') {
            $balance->amount = 0;
        }

        $balance->update();
        $client->addTransaction($this->payment, $balance->amount);
        if ($this->seRealizoPagoAtrasado($client->id)) {
            $clientServices = $this->clientRepository->getServiceActive($client->id);
            $this->actualizaPagoDelServicioActualEnFactura($clientServices);
            $this->sacarServiciosDelClienteDeAddressList($clientServices);
            $this->ponerFacturaComoPagado($client);
        }
    }

    public function updated()
    {
        $client = Client::findOrFail($this->payment->paymentable_id);
        $balance = $client->balance()->first();
        $payment = $this->payment->amount - $this->oldPayment->amount;
        $balance->amount = $balance->amount + $payment;
        if ($this->payment->payment_method()->first()->type == 'Acuerdo de Pago') {
            $payment = 0;
            $balance->amount = 0;
        }
        $balance->update();
        $client->updateTransaction($this->payment, $balance->amount);
    }

    public function deleted()
    {
        $client = Client::findOrFail($this->payment->paymentable_id);
        $balance = $client->balance()->first();

        if (!$this->payment->payment_method()->first()->type == 'Acuerdo de Pago') {
            $balance->amount -= $this->payment->amount;
            $client->deleteTransaction($this->payment);
        } else {
            $client->deleteTransactionWhenDeleteAgreement($this->payment);
            $client->deleteInvoiceWhenDeleteAgreement($this->payment);
            $balance->amount = $client->lastTransaction()->account_balance;
        }
        $balance->update();
    }

    public function elPagoCubrioElMontoDeLaFactura($client)
    {
        return $this->payment->amount >= $this->clientRepository->getCostAllServiceActive($client->id);
    }

    public function seRealizoPagoAtrasado($clientId)
    {
        $client = Client::find($clientId);
        if ($this->facturacionYaPaso($client) && $this->elPagoCubrioElMontoDeLaFactura($client) && $this->invoiceWithOutPayment($clientId) && $client->client_main_information()->first()->estado == 'Bloqueado') {
            return true;
        }
        return null;
    }

    public function invoiceWithOutPayment($clientId)
    {
        return ClientInvoice::where('client_id', $clientId)
            ->where('payment', 0)
            ->orderBy('created_at')
            ->first();
    }

    public function facturacionYaPaso($client)
    {
        return $client->billing_configuration()->first()->billing_date <= \Carbon\Carbon::now()->format('d');
    }

    public function modelHasAddressList($clientService)
    {
        return $clientService->modelName() == 'ClientInternetService' || $clientService->modelName() == 'ClientBundleService';
    }

    public function activarCliente($client)
    {
        return $client->client_main_information()->update(['estado' => 'Activo']);
    }

    public function sacarServiciosDelClienteDeAddressList($clientServices)
    {
        $services = ['bundle_service', 'internet_service', 'voz_service', 'custom_service'];
        foreach ($services as $service) {
            foreach ($clientServices->$service as $clientService) {
                if ($clientService->modelName() == "ClientInternetService") {
                    MikrotikRemoveClientServiceFromAddressList::dispatchAfterResponse($clientService);
                } elseif ($clientService->modelName() == "ClientBundleService") {
                    foreach ($clientService->service_internet()->get() as $internetService)
                        MikrotikRemoveClientServiceFromAddressList::dispatchAfterResponse($internetService);
                }
            }
        }
    }

    public function ponerFacturaComoPagado($client)
    {
        $invoiceWithoutPayments = $client->client_invoices()->where('payment', 0)->get();
        if ($invoiceWithoutPayments->count()) {
            foreach ($invoiceWithoutPayments as $invoiceWithoutPayment) {
                Log::info($invoiceWithoutPayment);
                $invoiceWithoutPayment->update(['payment' => 1]);
            }
        }
    }

    public function actualizaPagoDelServicioActualEnFactura($clientServices)
    {
        $services = ['bundle_service', 'internet_service', 'voz_service', 'custom_service'];
        foreach ($services as $service) {
            foreach ($clientServices->$service as $clientService) {
                $clientInvoice = ClientInvoiceService::where('client_serviceable_id', $clientService->id)
                    ->where('client_serviceable_type', get_class($clientService))
                    ->where('pay', false)
                    ->first();
                if ($clientInvoice) {
                    $clientInvoice->update(['pay' => true]);
                    return $clientInvoice;
                }

            }
        }
    }

}
