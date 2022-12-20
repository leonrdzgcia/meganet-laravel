<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateIpRedirect implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
//
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$value) return true;
        return $this->validateIp($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Existen IP con el formato incorrecto.';
    }

    public function validateIp($ip){
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return false;
        }
        return true;
    }

}
