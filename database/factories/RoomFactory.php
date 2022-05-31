<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location_id'=> 1,
            'temperature'=> rand(-20, 5),
            'blocks_quantity'=> rand(20, 30)
        ];
    }
}
