<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCreditCard implements Rule
{
    public function passes($attribute, $value)
    {
        // Remove any non-numeric characters from the credit card number
        $value = preg_replace('/[^0-9]/', '', $value);

        // Perform the Luhn algorithm check
        $sum = 0;
        $numDigits = strlen($value);
        $parity = $numDigits % 2;
        for ($i = 0; $i < $numDigits; $i++) {
            $digit = $value[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        return $sum % 10 == 0;
    }

    public function message()
    {
        return 'The :attribute is not a valid credit card number.';
    }
}
