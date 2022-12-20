<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateIpSeparatedByComma implements Rule
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
        return $this->separatedByCommas($value);
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

    public function separatedByCommas($argIp)
    {
        $ips = explode(',' ,$argIp);
        foreach ($ips as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
                return false ;
            }
        }
        return true;
    }
}
