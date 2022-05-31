<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StartTimeNotBiggerThanEndTime implements Rule
{

    protected $endTimeFieldName;

    protected $startTime;
    protected $startTimeFieldName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($startTime, $startTimeFieldName)
    {
        $this->startTime = $startTime;
        $this->startTimeFieldName = $startTimeFieldName;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) :bool
    {
        $this->endTimeFieldName = ucfirst(str_replace('_', ' ', $attribute));
        return $this->startTime < $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->startTimeFieldName.' cannot be bigger or equals '.$this->endTimeFieldName;
    }
}
