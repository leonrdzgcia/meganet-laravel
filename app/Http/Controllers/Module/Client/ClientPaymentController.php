<?php


namespace App\Http\Controllers\Module\Client;

use App\Http\Controllers\Controller;
use App\Http\HelpersModule\module\client\ClientPaymentDatatableHelper;
use App\Http\Repository\ClientRepository;
use App\Http\Requests\module\client\ClientPaymentRequest;
use App\Models\Client;
use App\Models\Module;
use App\Models\Payment;
use App\Models\MethodOfPayment;
use App\MyLibrary\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;

class ClientPaymentController extends Controller
{
    private $helper;
    private $clientRepository;

    public function __construct(ClientPaymentDatatableHelper $helper, ClientRepository $clientRepository)
    {
        $this->helper = $helper;
        $this->clientRepository = $clientRepository;
        $this->data['model'] = 'App\Models\Payment';
    }

    public function store(ClientPaymentRequest $request, $id)
    {
        return Client::find($id)->clientCreatePayment($request);
    }

    public function update(Request $request, $id)
    {
        $model = $this->data['model']::find($id);
        $model->updateFileUpload($request->file('file'));

        $request = collect($request->except(['file', 'date_payment']));
        $input = defined($this->data['model'] . '::MULTIPLE_RELATIONS') ?
            $request->except(collect($this->data['model']::MULTIPLE_RELATIONS)->keys()->toArray()) :
            $request->all();
        $this->saveRelationMultipleIfExist($this->data['model'], $model, $request, 'sync');

        $input = Utility::modifyValueForCheckbox($input, 'ClientPayment');

        return $model->update($input);
    }

    public function destroy($id)
    {
        $this->data['model']::where('id', $id)->first()->delete();
        return redirect()->back()->with('message', 'Payment Eliminado Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request);
    }

    public function getTotals($clientId)
    {
        $client = Client::where('id', $clientId)->first();
        $allPayments = MethodOfPayment::pluck('type', 'id')->toArray();

        $paymentsForClient = $client->payments()
            ->get()
            ->groupBy('payment_method_id');

        $payments = [];
        foreach ($paymentsForClient as $payment_method_id => $value) {
            $payments[$payment_method_id] = [
                'type' => $allPayments[$payment_method_id],
                'value' => $value->sum('amount')
            ];
        }
        return $payments;
    }

    public function getCostAllServiceActive($clientId)
    {
        return $this->clientRepository->getCostAllServiceActive($clientId);
    }

    public function getActiveServiceExpiration($clientId)
    {
        return $this->clientRepository->getActiveServiceExpiration($clientId);
    }

    public function getPrintPdf($payment_id)
    {
      $payment = Payment::find($payment_id);

    $serviceActive =  $this->clientRepository->getServiceActive($payment->paymentable_id);
    $services = [
        'bundle_service' => $serviceActive['bundle_service'],
        'internet_service' => $serviceActive['internet_service'] ,
        'voz_service' => $serviceActive['voz_service'] ,
        'custom_service' => $serviceActive['custom_service']
    ];

    $stringService = '';
    foreach($services as $service){
        if ($service->count()){
            $stringService .= $service[0]->description .' ,' ;
        }
    }
       if ($payment->isModelClient()){
            $client = Client::where('id', $payment->paymentable_id)->first();
            $data = [
                'payment_id' => $payment_id,
                'ticket_number' => $payment->receipt,
                'full_name' => $client->client_main_information()->first()->getClientNameWithFathersNamesAttribute(),
                'amount' => $payment->amount,
                'services' => $stringService ,
                'payment_period' => $payment->payment_period,
                'pay_up' => $this->getActiveServiceExpiration($payment->paymentable_id),
                'billing_expiration' => $this->getActiveServiceExpiration($payment->paymentable_id),
                'date' => $payment->date,
            ];
        }

      if (isset($data)){
            $pdf = PDF::loadView('meganet.module.client.billing.payment.pdf', compact('data'));
            return $pdf->setPaper('C6', 'porttrail')->stream();
        } else {
            return redirect()->back();
        }
    }
}
