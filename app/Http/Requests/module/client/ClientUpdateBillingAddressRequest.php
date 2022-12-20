<?php

namespace App\Http\Requests\module\client;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateBillingAddressRequest extends FormRequest
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
    public function rules()
    {
        return [
            'billing_name' => 'required',
            'billing_street' => 'required',
            'billing_zip_code' => 'required',
            'billing_city' => 'required',
        ];
    }
}
