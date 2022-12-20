<?php

namespace App\Http\Requests\module\crm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class CrmUpdateRequest extends FormRequest
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
            'name'=>'required',
            'father_last_name'=>'required',
            'mother_last_name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'crm_status'=>'required',
            'owner_id' => 'required',
            'instalation_date' => 'unique:crm_lead_information,instalation_date,' . $this->id
        ];
    }
}
