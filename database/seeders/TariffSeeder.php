<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tariffs_for_locations')->insert($this->getTariffsForLocations());
        DB::table('tariffs_for_temperature')->insert($this->getTariffsForTemperature());
    }

    /**
     * @return array
     */
    protected function getTariffsForLocations(): array
    {
        $locations_tariffs = [];

        foreach (Location::all()->pluck('id')->toArray() as $location_id) {
            $locations_tariffs[] = [
                'location_id' => $location_id,
                'price' => rand(10, 30),
            ];
        }
        return $locations_tariffs;
    }

    /**
     * @return array
     */
    protected function getTariffsForTemperature(): array
    {
        $temperature_tariffs = [];

        $low_temperature_limit = config('global.low_temperature_limit');
        $high_temperature_limit = config('global.high_temperature_limit');
        $temperature_step = 2;

        $price_step = 0.5;
        $current_price = 0;

        for ($temperature = $high_temperature_limit - $temperature_step;
             $temperature >= $low_temperature_limit; $temperature -= $temperature_step) {

            $temperature_tariffs[] = [
                'from_temperature' => $temperature,
                'to_temperature' => $temperature + $temperature_step,
                'price_increase_per_block' => $current_price
            ];

            $current_price += $price_step;
        }

        if (end($temperature_tariffs)['from_temperature'] !== $low_temperature_limit) {
            $current_last_element = end($temperature_tariffs);

            $temperature_tariffs[] = [
                'from_temperature' => $low_temperature_limit,
                'to_temperature' => $current_last_element['from_temperature'],
                'price_increase_per_block' => $current_last_element['price_increase_per_block'] + $price_step
            ];
        }

        return $temperature_tariffs;
    }
}
