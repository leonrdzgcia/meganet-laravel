<?php

namespace App\Jobs\Client\BillingService;

use App\Http\Controllers\Utils\TypeOfBillingController;
use App\Http\Repository\ClientRepository;
use App\Jobs\Client\RemoveServiceFromAddressList;
use App\Jobs\Mikrotik\MikrotikRemoveClientServiceFromAddressList;
use App\Models\Client;
use App\Models\ClientInvoice;
use App\Models\ClientInvoiceService;
use App\Models\Payment;
use App\Models\PaymentPromise;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RectifyBalanceAndCreateTransactionInClientService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $maxExceptions = 1;

    protected $clientService;
    protected $clientRepository;
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $clientService)
    {
        $this->clientService = $clientService;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $util = new TypeOfBillingController($this->clientService);
        $promisePayment = $this->isPromisePayment($this->clientService);

        $newBalanceAndPrice = $util->getNewBalanceAndPriceByTypeOfBilling();

        if (!$this->isPromisePaymentPayInTime($promisePayment) && isset($promisePayment)) {
            $this->clientService->service_in_address_list()->updateOrCreate(['deployed' => false]);
            throw new \Exception('Promesa de pago Incumplida, no tiene suficiente saldo y fue enviado a addresslist');
        }

        if ($newBalanceAndPrice) {
            $this->clientRepository->rectifyBalance($this->clientService, $newBalanceAndPrice);

            $clientRepository->addDebitTransactionForPaymentService($this->model, $this->clientService, $newBalanceAndPrice, null);

            $this->siNoTieneBanaceYNoEstaEnPromesaDePago($newBalanceAndPrice['new_balance'], $promisePayment);

            $this->siEsPromesaDePago($promisePayment);
            $this->siEsServicioBundle($clientRepository);
            $this->siEsServicioInternet($clientRepository);

            if ($this->sePagoEnTiempo($newBalanceAndPrice)) {
                $this->creaUnRegistroEnPaymentServiceConPagoEnTiempo();
                //TODO debe estar creado el invoice buscar los id de servicios y pasarle pagado
                $clientInvoiceServ = $this->actualizaPagoDelServicioActualEnFactura();
                if ($this->getCantidadServiciosDelInvoice() == $this->getCantidadServiciosPagadosDelInvoice()) {
                    ClientInvoice::find($clientInvoiceServ->client_invoice_id)->update(['payment' => 1 ]);
                }
            } else {
                // Significa que estoy en un pago recurrente y el pago esta atrasado
                if($this->noEsPagoEnTiempo()){
                    $this->agregaAlPeriodoDeGracia();
                }
            }
        } else {
            if ($this->clientService->isDeployed() && $this->modelHasAddressList($this->clientService)) {
                if ($this->isClientInternetService()) {
                    $this->agregarServicioAddressListEnFalsoParaDesplegarEnMikrotik();
                } else {
                    if($this->noEsPagoEnTiempo()){
                        $this->agregaAlPeriodoDeGracia();
                    }
                    if ($this->isClientBundleService()) {
                        $this->agregarServicioAddressListEnFalsoParaDesplegarEnMikrotik();
                    }
                    throw new \Exception('El servicio ya fue desplegado, no tiene suficiente saldo y fue enviado a addresslist');
                }
            } else {
                throw new \Exception('El servicio aun no se ha desplegado y no tiene suficiente saldo');
            }
            return null;
        }
    }

    public function isClientInternetService()
    {
        return $this->model == 'Internet';
    }

    public function isClientBundleService()
    {
        return $this->model == 'bundle';
    }

    public function modelHasAddressList($clientService)
    {
        return $clientService->modelName() == 'ClientInternetService' || $clientService->modelName() == 'ClientBundleService';
    }

    public function invoiceWithOutPayment($clientId)
    {
        return ClientInvoice::where('client_id', $clientId)
            ->where('payment', 0)
            ->orderBy('created_at')
            ->first();
    }

    public function getIfClientHaveInvoiceToday($clientId)
    {
        return ClientInvoice::where('client_id', $clientId)
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->first();
    }

    public function isPromisePayment($service)
    {
        foreach ($service['client']['payments'] as $payments) {
            foreach ($payments['payment_promise'] as $promise) {
                if (\Carbon\Carbon::parse($promise->court_date)->isToday()) {
                    return [
                        'id' => $promise->id,
                        'payment_id' => $promise->payment_id,
                        'amount' => $promise->amount,
                    ];
                }
            }
        }
    }

    public function getNextExpirationDate($promisePayment)
    {
        $input = PaymentPromise::where('id', '>', $promisePayment['id'])->firstOrFail();
        return \Carbon\Carbon::parse($input->court_date)->format('Y-m-d H:i:s');
    }

    public function isPromisePaymentPayInTime($promisePayment)
    {
        if (isset($promisePayment)) {
            $lastPayment = Payment::where('id', $promisePayment['payment_id'])->orderBy('created_at', 'desc')->first();
            $indexPayment = PaymentPromise::where('payment_id', $promisePayment['payment_id'])->count();
            $paymentPromise = PaymentPromise::where('payment_id', $promisePayment['payment_id'])->orderBy('created_at', 'desc')->first();

            if ($lastPayment) {
                return $lastPayment->amount >= $promisePayment['amount'] &&
                    $lastPayment->created_at <= $paymentPromise->court_date &&
                    $this->payIndex($lastPayment, $promisePayment) <= $indexPayment;
            }
        }
        return null;
    }

    public function payIndex($payment, $promisePayment)
    {
        return $payment->id - $promisePayment['payment_id'];
    }

    public function siNoTieneBanaceYNoEstaEnPromesaDePago($balance, $promisePayment)
    {
        if (($balance < 0) && !isset($promisePayment)) {
            return $this->clientService->service_in_address_list()->updateOrCreate(['deployed' => false]);
        }
    }

    public function siEsPromesaDePago($promisePayment)
    {
        if (isset($promisePayment)) {
            if ($this->clientService->bundle_id) {
                $this->clientService->update([
                    'contract_start_date' => Carbon::now()->toDateTimeString(),
                    'contract_end_date' => $this->getNextExpirationDate($promisePayment)
                ]);

                $clintInternetServices = $this->clientService->service_internet()->get();

                if ($clintInternetServices) {
                    foreach ($clintInternetServices as $clientService) {
                        $clientService->first()->update([
                            'start_date' => Carbon::now()->toDateTimeString(),
                            'finish_date' => $this->getNextExpirationDate($promisePayment)]);
                        if ($this->modelHasAddressList($clientService)) {
                            MikrotikRemoveClientServiceFromAddressList::dispatchAfterResponse($clientService);
                        }
                    }
                }
            }
            if ($this->clientService->internet_id) {
                $this->clientService->update([
                    'start_date' => Carbon::now()->toDateTimeString(),
                    'finish_date' => $this->getNextExpirationDate($promisePayment)]);

                if ($this->clientService->isDeployed() && $this->modelHasAddressList($this->clientService)) {
                    MikrotikRemoveClientServiceFromAddressList::dispatchAfterResponse($this->clientService);
                }
            }
            return null;
        }
    }

    public function siEsServicioBundle($clientRepository)
    {
        if ($this->clientService->bundle_id) {
            $this->clientService->update([
                'contract_start_date' => Carbon::now()->toDateTimeString(),
                'contract_end_date' => $this->clientRepository->getActiveServiceExpiration($this->clientService->client_id)
            ]);

            $clintInternetServices = $this->clientService->service_internet()->get();

            if ($clintInternetServices) {
                foreach ($clintInternetServices as $clientService) {
                    $clientService->first()->update([
                        'start_date' => Carbon::now()->toDateTimeString(),
                        'finish_date' => $clientRepository->getActiveServiceExpiration($this->clientService->client_id)]);

                    if ($this->modelHasAddressList($clientService)) {
                        MikrotikRemoveClientServiceFromAddressList::dispatchAfterResponse($clientService);
                    }
                }
            }
        }
    }

    public function siEsServicioInternet($clientRepository)
    {
        if ($this->clientService->internet_id) {
            $this->clientService->update([
                'start_date' => Carbon::now()->toDateTimeString(),
                'finish_date' => $clientRepository->getActiveServiceExpiration($this->clientService->client_id)]);

            if ($this->clientService->isDeployed() && $this->modelHasAddressList($this->clientService)) {
                MikrotikRemoveClientServiceFromAddressList::dispatchAfterResponse($this->clientService);
            }
        }
    }

    public function sePagoEnTiempo($newBalanceAndPrice)
    {
        return isset($newBalanceAndPrice['payment_in_time']);
    }

    public function creaUnRegistroEnPaymentServiceConPagoEnTiempo()
    {
        $this->clientService->client_payment_service()->create();
    }

    public function actualizaPagoDelServicioActualEnFactura()
    {
        $clientInvoice = ClientInvoiceService::where('client_serviceable_id', $this->clientService->id)
            ->where('client_serviceable_type', get_class($this->clientService))
            ->where('pay', false)->first();
             $clientInvoice->update(['pay' => true]);
             return $clientInvoice;
    }

    public function getCantidadServiciosPagadosDelInvoice(){
        return ClientInvoiceService::where('client_serviceable_id', $this->clientService->id)
            ->where('client_serviceable_type', get_class($this->clientService))
            ->where('pay', 1)
            ->get()
            ->count();
    }

    public function getCantidadServiciosDelInvoice(){
        return ClientInvoiceService::where('client_serviceable_id', $this->clientService->id)
            ->where('client_serviceable_type', get_class($this->clientService))
            ->get()
            ->count();
    }

    public function noEsPagoEnTiempo(){
        return !$this->clientService->client_grace_period;
    }

    public function agregaAlPeriodoDeGracia(){
       $this->clientService->client_grace_period()->create(['client_id' => $this->clientService->client_id]);
    }

    public function agregarServicioAddressListEnFalsoParaDesplegarEnMikrotik(){
      return  $this->clientService
            ->service_in_address_list()
            ->updateOrCreate(['deployed' => false]);
    }


}
