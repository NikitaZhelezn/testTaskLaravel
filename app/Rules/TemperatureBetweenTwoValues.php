<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TemperatureBetweenTwoValues implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value > config('global.low_temperature_limit')
            && $value < config('global.high_temperature_limit');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Sorry, we don't have blocks with the such temperature.";
    }
}
