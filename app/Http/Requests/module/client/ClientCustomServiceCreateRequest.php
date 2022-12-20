<?php

namespace App\Http\Requests\module\client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientCustomServiceCreateRequest extends FormRequest
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
            'amount' => 'required',
            'unity' => 'required',
            'start_date' => 'required',
            'pay_period' => 'required',
            'estado' => 'required',

            'discount_percent' => Rule::requiredIf($request->discount == true),
            'start_date_discount' =>  Rule::requiredIf($request->discount == true),
            'end_date_discount' => Rule::requiredIf($request->discount == true),
            'discount_message' => Rule::requiredIf($request->discount == true),

            'payment_type' => 'required',
            'deferred_payment_in_month' => Rule::requiredIf($request->payment_type == 'Pago diferido'),
        ];
    }
}
