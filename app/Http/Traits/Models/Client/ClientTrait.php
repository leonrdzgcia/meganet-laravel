<?php


namespace App\Http\Traits\Models\Client;

use App\Http\Controllers\FileController;
use App\Http\Controllers\Utils\ReceiptController;
use App\Models\Client;
use App\Models\Payment;
use App\Models\ClientInvoice;
use App\MyLibrary\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait ClientTrait
{
    public function clientCreateClientMainInformation($request)
    {
        $key = collect(config('module.client.constants.ClientMainInformation.FIELDS'))->keys()->toArray();
        $this->client_main_information()->create(\Illuminate\Support\Arr::only($request->all(), $key));
        return $this;
    }

    /**
     * @param $request
     * @return $this
     */
    public function clientCreateClientAdditionalInformation($request)
    {
        $key = collect(config('module.client.constants.ClientAdditionalInformation.FIELDS'))->keys()->toArray();
        $this->client_additional_information()->create(\Illuminate\Support\Arr::only($request->all(), $key));
        return $this;
    }

    public function clientCreateClientUser($model)
    {
        return $this->user()->create([
            'user' => $model->client_main_information()->first()->user
        ]);
    }


    /**
     * @param $field
     * @param $val
     */
    public function clientSavePassword($field, $val)
    {
        $this->client_main_information()->update([
            $field => $val
        ]);
    }

    /**
     * @param $field
     * @param $val
     */
    public function clientSaveUser($field, $val)
    {
        $this->client_main_information()->update([
            $field => $val
        ]);
    }

    public function clientFullName()
    {
        if ($this->client_additional_information && $this->client_additional_information->name) {
            return $this->client_additional_information->name . ' ' . $this->client_additional_information->fathers_last_name . ' ' . $this->client_additional_information->mothers_last_name;
        }
        return $this->client_main_information->name ?? '';
    }

    public function clientGetName()
    {
        return $this->client_main_information()->first()->name;
    }

    public function clientGetUser()
    {
        return $this->client_main_information()->first()->user;
    }

    public function clientGetPassword()
    {
        return $this->client_main_information()->first()->password;
    }

    public function clientGetStatus()
    {
        return $this->client_main_information()->first()->status;
    }

    public function clientCreatePayment($request)
    {
        $input = $request->except(['file','date_payment']);
        $input['send_receipt_after_payment'] = isset($request->send_receipt_after_payment) && $request->send_receipt_after_payment == true;
        $input['add_by'] = Auth::user()->id;
        $input['date'] = Carbon::now()->toDateTimeString();
        $input['number'] = $this->setPaymentNumber();
        $input['receipt'] = ReceiptController::getStaticReceiptForClient();

        $this->receipt()->create(['receipt' => $input['receipt']]);
        $input = Utility::modifyValueForCheckbox($input, 'ClientPayment');

//        ($this->isPromisePayment()) ? $input['payment_id'] = $this->isPromisePayment()->id : $input['payment_id'] = null;

        $payment = $this->payments()->create($input);
        if ($request->file) $payment->uploadFile($request->file);
        return $payment;
    }

    public function clientCreatePaymentAgreement($values)
    {
        $values['add_by'] = Auth::user()->id;
        $values['date'] = Carbon::now()->toDateTimeString();
        $values['number'] = $this->setPaymentNumber();
        $values['receipt'] = ReceiptController::getStaticReceiptForClient();
        $this->receipt()->create(['receipt' => $values['receipt']]);
        $payment = $this->payments()->create($values);

        return $payment;
    }

    public function setPaymentNumber()
    {
        $count = Payment::count();
        if ($count) {
            return Carbon::now()->format('ym') . '000' . $count + 1;
        }
        return Carbon::now()->format('ym') . '000' . '1';
    }

    public function clientCreateTransaction($request)
    {
        $input = $request->except('input-price-transaction');
        $input = Utility::modifyValueForCheckbox($input, 'ClientTransaction');

        //TODO QUITAR Y ARREGLAR
        $input['iva'] = $input['account_balance'] ?? 0;
        $input['account_balance'] = $input['account_balance'] ?? 0;
        $input['company_balance'] = $input['company_balance'] ?? 0;
        $input['client_id'] = $input['client_id'] ?? $this->id;
        $input['movement'] = $input['movement'] ?? '';

        return $this->transactions()->create($input);
    }

    public function clientCreateBalance()
    {
        return $this->balance()->create();
    }

    public function addTransaction($payment, $amountBalance)
    {
        $this->transactions()->create([
            'date' => Carbon::now()->toDateTimeString(),
            'credit' => $payment->amount,
            'account_balance' => $amountBalance,
            'description' => $payment->getPaymentMethod(),
            'category' => 'Pago',
            'cantidad' => '1',
            'client_id' => $this->id,
            'type' => 'credit',
            'price' => $payment->amount,
            'iva' => 0,
            'total' => $payment->amount,
            'from_date' => null,
            'to_date' => null,
            'comment' => $payment->comment,
            'period' => $payment->payment_period,
            'add_to_invoice' => false,
            'company_balance' => $amountBalance,
            'movement' => '+ ' . $payment->amount,
            'service_name' => null,
            'invoice' => null,
            'is_payment' => true,
            'payment_id' => $payment->id,
        ]);
    }

    public function clientCreateDocument($request)
    {
        $document = $this->createClientDocument($request->except('file'));
        return $this->uploadAndSaveDocumentUploaded($request->file, $document, $this->id);
    }

    public function createClientDocument($input)
    {
        if (isset($input['show'])) $input['show'] = ($input['show'] == 'true');
        $input = $this->addCreatorId($input);
        return $this->documents()->create($input);
    }

    public function addCreatorId($input){
        $input['added_by_id'] = Auth::user()->id;
        return $input;
    }

    public function uploadAndSaveDocumentUploaded($file, $document, $idClient)
    {
        $file_process = new FileController;
        $module_path = 'client/'. $idClient .'/document';
        $properties = $file_process->processSingleFileAndReturnProperties($file, $module_path, $document->id);

        $document->file()->create($properties);
        $file->storeAs('/public/'. $module_path .'/'. $document->id, $properties['name']);

        return true;
    }

    public function clientCreateReminderConfiguration()
    {
        return $this->reminder_configuration()->create([
            'activate_reminders' => '1',
            'type_of_message' => 'Mail + SMS',
            'reminder_1_days' => '5',
            'reminder_2_days' => '3',
            'reminder_3_days' => '1'
        ]);
    }

    public function clientCreateBillingConfiguration()
    {
        return $this->billing_configuration()->create([
            'billing_activated' => true,
            'type_billing_id' => '1',
            'period' => '1',
            'billing_date' => Carbon::Now()->day,
            'billing_expiration' => Carbon::Now()->day,
            'grace_period' => '90',
        ]);
    }

    public function deleteTransaction($payment){
        return $this->transactions()->where('payment_id',$payment->id)->delete();
    }

    public function deleteTransactionWhenDeleteAgreement($payment){
        return $this->transactions()
            ->where('description','Rectificación de debito por Acuerdo de Pago')
            ->orWhere('description','Acuerdo de Pago')
            ->where('price',$payment->amount)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->delete();
    }

    public function lastTransaction(){
        return $this->transactions()->orderBy('created_at', 'desc')->first();
    }

    public function deleteInvoiceWhenDeleteAgreement($payment){
        $clientInvoiceAgreement = $this->client_invoices()
            ->where('estado','Pagado')
            ->where('total',$payment->amount)
            ->orderBy('created_at', 'desc')
            ->first();

        $clientInvoiceCancelByAgreements = ClientInvoice::where('note',$clientInvoiceAgreement->number)->get();

        foreach ($clientInvoiceCancelByAgreements as $clientInvoiceCancelByAgreement){
            $clientInvoiceCancelByAgreement->update(['estado' => 'Pagar (del saldo de la cuenta)', 'note' => null ]);
        }
        $clientInvoiceAgreement->delete();
    }
    public function isPromisePayment()
    {
     return  $this->payments()->where('payment_promise', true)
            ->where(function ($query) {
                $query->whereDate('first_court_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
                    ->orWhereDate('second_court_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
                    ->orWhereDate('third_court_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'));
            })
            ->first();
    }

}
