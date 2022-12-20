<?php

namespace App\Jobs\Client\BillingService;

use App\Models\ClientInvoiceService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Repository\ClientRepository;
use App\Http\Controllers\Utils\TypeOfBillingController;
use Illuminate\Support\Facades\Log;
use App\Models\ClientInvoice;


class RectifyBalanceAndCreateTransactionInClientServiceFirstTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $newBalanceAndPrice = $util->getNewBalanceAndPriceByTypeOfBilling();

        if ($newBalanceAndPrice) {
            $clientRepository->rectifyBalance($this->clientService, $newBalanceAndPrice);

            $clientRepository->addDebitTransactionForPaymentService($this->model, $this->clientService, $newBalanceAndPrice);

            if ($this->ifEsUnBundleService()) {
                $this->actualizaBundleServiceConElDiaQueIniciaYTerminaElContrato();
                $this->actualizaLosInternetServiceQueEsteEnElPaqueteConElDiaQueIniciaYTerminaElContrato();
            }

            if ($this->ifEsUnInternetService()) {
                $this->actualizaElInternetServiceConElDiaQueIniciaYTermina();
            }

            if ($this->sePagoEnTiempo($newBalanceAndPrice))
            {
                $this->creaUnRegistroEnPaymentServiceConPagoEnTiempo();
                    //TODO debe estar creado el invoice buscar los id de servicios y pasarle pagado
                $clientInvoiceService = $this->actualizaPagoDelServicioActualEnFactura();

                if ($this->getCantidadServiciosDelInvoice() == $this->getCantidadServiciosPagadosDelInvoice()) {
                    ClientInvoice::find($clientInvoiceService->client_invoice_id)->update(['payment' => 1 ]);
                }
            }

        } else {
            // TODO si no tiene balance y es un cliente recurrente crear factura

            throw new \Exception('El servicio aun no se ha desplegado y no tiene suficiente saldo servicio id:'. $this->clientService->internet_id);
        }
    }

    public function ifEsUnBundleService()
    {
        return $this->clientService->bundle_id;
    }

    public function actualizaBundleServiceConElDiaQueIniciaYTerminaElContrato()
    {
        $this->clientService->update([
            'contract_start_date' => Carbon::now()->toDateTimeString(),
            'contract_end_date' => $this->clientRepository->getActiveServiceExpiration($this->clientService->client_id)]);
    }

    public function actualizaLosInternetServiceQueEsteEnElPaqueteConElDiaQueIniciaYTerminaElContrato()
    {
        $clintInternetServices = $this->clientService->service_internet;

        if ($clintInternetServices->count()) {
            foreach ($clintInternetServices as $clientService){
                $clientService->update([
                    'start_date' => Carbon::now()->toDateTimeString(),
                    'finish_date' => $this->clientRepository->getActiveServiceExpiration($this->clientService->client_id)]);
            }
        }
    }

    public function ifEsUnInternetService()
    {
        return $this->clientService->internet_id;
    }

    public function actualizaElInternetServiceConElDiaQueIniciaYTermina()
    {
        $this->clientService->update([
            'start_date' => Carbon::now()->toDateTimeString(),
            'finish_date' => $this->clientRepository->getActiveServiceExpiration($this->clientService->client_id)]);
    }

    public function sePagoEnTiempo($newBalanceAndPrice)
    {
        return isset($newBalanceAndPrice['payment_in_time']);
    }

    public function creaUnRegistroEnPaymentServiceConPagoEnTiempo()
    {
        $this->clientService->client_payment_service()->create();
    }

    public function actualizaPagoDelServicioActualEnFactura(){
        $clientInvoiceServ = ClientInvoiceService::where('client_serviceable_id',$this->clientService->id)
            ->where('client_serviceable_type', get_class($this->clientService))
            ->where('pay',false)
            ->first();
        $clientInvoiceServ->update(['pay' => true]);
        return $clientInvoiceServ;
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

}
