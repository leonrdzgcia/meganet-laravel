<?php

namespace App\Http\Controllers\Module\Client;

use App\Models\ClientInvoice;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\HelpersModule\module\client\ClientInvoiceDatatableHelper;
use App\Http\Requests\module\client\ClientInvoiseRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;


class ClientInvoiceController extends Controller
{

    private $helper;

    public function __construct(ClientInvoiceDatatableHelper $helper)
    {
        $model = 'ClientInvoice';
        $this->data['url'] = 'meganet.module.' . Str::lower($model);
        $this->data['module'] = $model;
        $this->data['model'] = 'App\Models\\' . $model;
        $this->data['group'] = 'clientInvoice';
        $this->helper = $helper;
    }

    public function store(ClientInvoiseRequest $request, $id)
    {
//        return Client::find($id)->clientCreateInvoise($request);
    }

    public function destroy($id)
    {
        $this->data['model']::where('id', $id)->first()->delete();
        return redirect()->back()->with('message', 'Factura Eliminada Correctamente');
    }

    public function table(Request $request)
    {
        return $this->helper->fetch_datatable_data($request, $this->data['model']);
    }

    public function getPrintPdf($invoice_id)
    {
     $client_invoice = ClientInvoice::findOrFail($invoice_id);
     $client_invoice_type = $client_invoice->type;

      if ($client_invoice_type == ClientInvoice::TYPE_INVOICE_SERVICES){
        $data = $this->invoice_services($invoice_id);
      }

      if ($client_invoice_type == ClientInvoice::TYPE_INVOICE_SURCHARGE_DEFAULTER){
        $data = $this->invoice_services_defaulter($client_invoice);
      }

      if (isset($data)){
            $pdf = PDF::loadView('meganet.module.client.billing.invoice.pdf', compact('data'));
            return $pdf->stream();
        } else {
            return redirect()->back();
        }
    }

    public function invoice_services($invoice_id){
        $client = Client::with([
            'client_main_information.state',
            'client_invoices' => function ($query) use ($invoice_id) {
                $query->where('id', $invoice_id);
            },
            'balance',
            'client_main_information.municipality',
            'client_main_information.colony'
            ,'bundle_service' => function ($query) {
                $query->where(function ($query) {
                    $query->where('estado', 'Activado');
                });
            }
            ,'internet_service' => function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('client_bundle_service_id');
                })
                    ->where(function ($query) {
                        $query->where('estado', 'Activado');
                    });
            }
            ,'voz_service' => function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('client_bundle_service_id');
                })
                    ->where(function ($query) {
                        $query->where('estado', 'Activado');
                    });
            }
            ,'custom_service' => function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('client_bundle_service_id');
                })
                    ->where(function ($query) {
                        $query->where('estado', 'Activado');
                    })

                    ->where(function ($query) {
                        $query->where('charged', 1)
                        ->where('payment_type','=','Pago recurrente');
                    })
                    ->orWhere(function ($query) {
                        $query->where('charged', 0)
                        ->where('payment_type','!=','Pago recurrente');
                    });

            }])
            ->whereHas('client_invoices' , function ($query) use ($invoice_id) {
                $query->where('id', $invoice_id);
            })
            ->first();

        if ($client && $client['client_invoices']->count()) {

            if ($client['bundle_service']->count()) {
                $bundle = $client['bundle_service'][0]->bundle()->get();
            } else {
                $bundle = null;
            }

            if ($client['internet_service']->count()) {
                $internet = $client['internet_service'][0]->internet()->get();
            } else {
                $internet = null;
            }

            if ($client['voz_service']->count()) {
                $voz = $client['voz_service'][0]->voz()->get();
            } else {
                $voz = null;
            }

            if ($client['custom_service']->count()) {
                $custom = $client['custom_service'][0]->custom()->get();
            } else {
                $custom = null;
            }

            $services = [
                'bundle_service' => $bundle,
                'internet_service' => $internet,
                'voz_service' => $voz,
                'custom_service' => $custom,
            ];
            $client_services = [];

            $i = 0;
            $prices = 0;
            $ivaSum = 0;
            foreach ($services as $key => $service) {
                if ($service) {
                    foreach ($service as $k => $v) {
                        $iva = $v->price / 100 * $v->tax;
                        $client_services[] = [
                            'number' => $i + 1,
                            'service_name' => $v->title,
                            'iva_porcent' => $v->tax,
                            'iva' => !$v->tax_include ? $iva : 0,
                            'monto' => $v->price,
                        ];

                        $ivaSum = $ivaSum + !$v->tax_include ? $iva : 0;
                        $prices = $prices + $v->price;
                    }
                }
            }
            if ($client['balance']['amount'] < 0) {
                $debit = $client['balance']['amount'];
            } else {
                $debit = 0;
            }

           return  [
                'client_name_with_fathers_names' => $client['client_main_information']['client_name_with_fathers_names'] ?? '',
                'street' => $client['client_main_information']['street'] ?? '',
                'state' => $client['client_main_information']['state']['name'] ?? '',
                'municipality' => $client['client_main_information']['municipality']['name'] ?? '',
                'colony' => $client['client_main_information']['colony']['name'] ?? '',
                'zip' => $client['client_main_information']['zip'] ?? '',
                'number' => $client['client_invoices'][0]['number'] ?? '',
                'created_at' => Carbon::parse($client['client_invoices'][0]['created_at'])->toDateString() ?? '',
                'payment' => $client['client_invoices'][0]['payment'],
                'debit' => $debit ?? '',
                'sub_total' => $prices ?? '',
                'total' => $prices + $ivaSum ?? '',
                'total_iva' => $prices / 100 * $ivaSum ?? '',
                'pay_up' => $client['client_invoices'][0]['pay_up'] ?? '',
                'client_services' => $client_services ?? '',
                'estado' => $client['client_invoices'][0]['estado'] ?? '',
                'note' => $client['client_invoices'][0]['note'] ?? ''
            ];

        }
        return [];
    }

    public function invoice_services_defaulter($client_invoice)
    {
       $client = $client_invoice->client()->first();
         if ($client){
            $client_services[] = [
                'number' => 1,
                'service_name' => 'Recargo',
                'iva_porcent' => '0',
                'iva' => '0',
                'monto' => '99.0',
            ];

            return  [
                'client_name_with_fathers_names' => $client->client_main_information()->first()->client_name_with_fathers_names,
                'street' => $client->client_main_information()->first()->street,
                'state' => $client->client_main_information()->first()->getStateName(),
                'municipality' => $client->client_main_information()->first()->getMunicipalityName(),
                'colony' => $client->client_main_information()->first()->getColonyName(),
                'zip' => $client->client_main_information()->first()->zip,
                'number' => $client_invoice->number,
                'created_at' => Carbon::parse($client_invoice->created_at)->toDateString(),
                'payment' => $client_invoice->payment,
                'debit' => '99.0',
                'sub_total' => '99.0',
                'total' => '99.0',
                'total_iva' => '0',
                'pay_up' => '',
                'client_services' => $client_services,
                'estado' => $client_invoice->estado,
                'note' => $client_invoice->note,
            ];
        }
    }

    public function hidden($value){
        return $value ? 'visible' : 'hidden';
    }

    public function createManualClientInvoice($id){
        return $id;
    }

}
