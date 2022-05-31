<?php

namespace App\Services\PriceCalculationService\PriceCalculationWithDatabaseTariffs;

use App\Http\Requests\Request;
use App\Models\Location;
use App\Services\PriceCalculationService\PriceCalculationServiceInterface;
use App\Services\PriceCalculationService\PriceCalculationWithDatabaseTariffs\TariffModels\LocationTariff;
use App\Services\PriceCalculationService\PriceCalculationWithDatabaseTariffs\TariffModels\TemperatureTariff;

class TariffCalculation implements PriceCalculationServiceInterface
{

    /**
     * @param Request $request
     * @param Location $location
     * @param array $available_blocks_for_location
     * @return array
     */
    public function getPriceAndBlocksQuantity(Request $request, Location $location,
                                              array   $available_blocks_for_location): array
    {
        $blocks_quantity = $this->getNeededQuantityOfRooms($request->capacity);
        $blocks_in_rooms_to_order = $this->getNeededBlocksPerRoom($available_blocks_for_location, $blocks_quantity);
        $price = $this->getPriceForChoosenBlocks($blocks_in_rooms_to_order, $location->id, $blocks_quantity);
        return [
            'blocks_quantity' => $blocks_quantity,
            'price' => $price
        ];
    }

    /**
     * @param Request $request
     * @param array $available_blocks_for_location
     * @return array
     */
    public function getOrder(Request $request, array $available_blocks_for_location): array
    {
        $blocks_quantity = $this->getNeededQuantityOfRooms($request->capacity);
        $blocks_in_rooms_to_order = $this->getNeededBlocksPerRoom($available_blocks_for_location, $blocks_quantity);

        return $blocks_in_rooms_to_order;
    }


    /**
     * @param int $capacity
     * @return false|float
     */
    protected function getNeededQuantityOfRooms(int $capacity): float
    {
        return ceil($capacity / config('global.block_capacity'));
    }


    /**
     * @param array $available_blocks_for_location
     * @param int $blocks_quantity
     * @return array
     */
    protected function getNeededBlocksPerRoom(array $available_blocks_for_location, int $blocks_quantity): array
    {
        $needed_blocks = $blocks_quantity;
        $current_order = [];

        foreach ($available_blocks_for_location as $room) {

            $current_order[] = [
                'room_id' => $room['room_id'],
                'blocks_quantity' => $room['location_blocks_available_quantity'] - $needed_blocks < 0
                    ? $room['location_blocks_available_quantity']
                    : $needed_blocks,
                'temperature' => $room['temperature']
            ];

            $needed_blocks = $blocks_quantity - $room['location_blocks_available_quantity'];
            if ($room['location_blocks_available_quantity'] - $needed_blocks > 0) break;

        }

        if ($needed_blocks > 0) return [];

        return $current_order;
    }

    /**
     * @param array $blocks_in_rooms_to_order
     * @param int $location_id
     * @param int $blocks_quantity
     * @return false|float|int
     */
    protected function getPriceForChoosenBlocks(array $blocks_in_rooms_to_order, int $location_id, int $blocks_quantity)
    {
        $location_tariffs = $this->getLocationTariffs();
        $temperature_tariffs = $this->getTemperatureTariffs();

        if (empty($blocks_in_rooms_to_order)) return false;

        $price = $blocks_quantity * $location_tariffs
                ->where('location_id', '=', $location_id)->first()->price;

        foreach ($blocks_in_rooms_to_order as $room) {
            $price += $temperature_tariffs->where('from_temperature', '<=', $room['temperature'])
                    ->where('to_temperature', '>=', $room['temperature'])
                    ->first()->price_increase_per_block * $room['blocks_quantity'];
        }

        return $price;
    }

    /**
     * @return LocationTariff[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getLocationTariffs()
    {
        return LocationTariff::all();
    }

    /**
     * @return TemperatureTariff[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getTemperatureTariffs()
    {
        return TemperatureTariff::all();
    }

}
