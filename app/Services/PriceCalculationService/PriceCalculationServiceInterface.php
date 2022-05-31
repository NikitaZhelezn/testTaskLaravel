<?php

namespace App\Services\PriceCalculationService;

use App\Http\Requests\Request;
use App\Models\Location;

interface PriceCalculationServiceInterface
{
    /**
     * @param Request $request
     * @param Location $location
     * @param array $available_blocks_for_location
     * @return array
     */
    public function getPriceAndBlocksQuantity(Request $request, Location $location,
                                              array $available_blocks_for_location): array;

    /**
     * @param Request $request
     * @param array $available_blocks_for_location
     * @return array
     */
    public function getOrder(Request $request, array $available_blocks_for_location): array;

}
