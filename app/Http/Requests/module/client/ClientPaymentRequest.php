<?php

namespace App\Http\Requests\module\client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class ClientPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'payment_method_id' => 'required',
            // TODO debe agregarse validacion para que sea de tipo flotante
            'amount' => 'required',
            'payment_period' => 'required',
            'receipt' => 'required'
        ];
    }
}
