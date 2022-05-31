<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DurationISNotLessThan implements Rule
{
    protected $startTime;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($startTime)
    {
        $this->startTime = $startTime;
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
        $startTime = new \DateTime($this->startTime);
        $endTime = new \DateTime($value);
        return $startTime->diff($endTime)->d >= config('global.min_time_storage_in_days');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your duration is less than '.config('global.min_time_storage_in_days').' days.';
    }
}
