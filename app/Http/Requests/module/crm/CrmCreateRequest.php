<?php

namespace App\Http\Requests\module\crm;

use App\Rules\ValidateSameNameInCrmOrClientTable;
use App\Rules\ValidateUniqueEmailForClientAndCrm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class CrmCreateRequest extends FormRequest
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
            'name'=> [new ValidateSameNameInCrmOrClientTable($request), 'required'],
            'father_last_name'=>'required',
            'mother_last_name'=>'required',
            'email'=> [new ValidateUniqueEmailForClientAndCrm($request), 'email'],
            'phone'=>'required',
            'crm_status'=>'required',
            'owner_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.unique.crm_main_information' => 'A asdasd is required',
        ];
    }
}
