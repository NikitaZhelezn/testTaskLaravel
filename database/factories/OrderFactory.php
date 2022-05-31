<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{

    protected $order_codes = [];
    const ORDER_CODE_LENGTH = 12;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start_time = $this->faker->dateTimeBetween('now - 5 days', 'now + 20 days');

        return [
            'capacity' => rand(40,100),
            'code' => $this->generateCode(),
            'start_time' => $start_time,
            'finish_time' => $this->faker->dateTimeBetween($start_time, 'now + 2 months')
        ];
    }

    protected function generateCode()
    {
        do {
            $code = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                ceil(static::ORDER_CODE_LENGTH / strlen($x)))), 1, static::ORDER_CODE_LENGTH);
        }
        while(in_array($code, $this->order_codes));
        $this->order_codes[] = $code;
        return $code;
    }

}
