<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidExpirationDate implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the expiration date is a valid date
        if (!strtotime($value)) {
            return false;
        }
        
        // Get the current date
        $currentDate = now();
        
        // Convert the expiration date to a DateTime object
        $expirationDate = \DateTime::createFromFormat('Y-m-d', $value);

        // Check if the expiration date is after the current date
        return $expirationDate > $currentDate;
    }

    public function message()
    {
        return 'The :attribute must be a date after the current date.';
    }
}
