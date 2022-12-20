<?php

namespace App\Http\Requests\module\client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Rules\IfChangeRecurrentToOtherTypeOfBilling;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ClientInformationRequest extends FormRequest
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
        $client_id = $request->route()->id;
        return [
           'name' => 'required',
           'type_of_billing_id' =>  new IfChangeRecurrentToOtherTypeOfBilling($client_id),
        ];
    }
}
