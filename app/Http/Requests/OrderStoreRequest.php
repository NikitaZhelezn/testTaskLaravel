<?php

namespace App\Http\Requests;

use App\Rules\DurationISNotBiggerThan;
use App\Rules\DurationISNotLessThan;
use App\Rules\StartTimeNotBiggerThanEndTime;
use App\Rules\TemperatureBetweenTwoValues;

class OrderStoreRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location_id' => 'required|numeric|exists:locations,id',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'finish_time' => ['bail','required', 'date_format:Y-m-d H:i:s',
                new StartTimeNotBiggerThanEndTime($this->start_time, 'start time'),
                new DurationISNotBiggerThan($this->start_time),
                new DurationISNotLessThan($this->start_time),
            ],
            'temperature' => ['required', 'numeric', new TemperatureBetweenTwoValues()],
            'capacity' => 'required|numeric'
        ];
    }
}
