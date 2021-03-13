<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use phpDocumentor\Reflection\Types\Boolean;

class IranID implements Rule
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
        /* check if the value matches the structure */
        if(!preg_match('/^[0-9]{10}$/', $value))
        {
            return false;
        }
        /* check weather all digits are the same or not */
        for($i = 0; $i < 10; $i++)
        {
            if(preg_match('/^'.$i.'{10}$/', $value))
            {
                return false;
            }
        }
        /* calculate the sum of first 9 digits */
        for($i = 0, $sum = 0; $i < 9; $i++)
        {
            $sum += ((10 - $i) * intval(substr($value, $i,1)));
        }
        $remaining = $sum % 11;
        $parity = intval(substr($value, 9,1));
        if(($remaining < 2 && $remaining == $parity) || ($remaining >= 2 && $remaining == 11 - $parity))
        {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'کد ملی وارد شده نامعتبر است';
    }
}
