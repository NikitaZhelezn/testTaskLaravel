<?php

namespace App\Services\PriceCalculationService;

use App\Http\Requests\Request;

interface PriceCalculationServiceInterface
{
    /**
     * @param Request $request
     * @param int $location_id
     * @param array $available_blocks_for_location
     * @return array
     */
    public function getPriceAndBlocksQuantity(Request $request, int $location_id, array $available_blocks_for_location): array;

    /**
     * @param Request $request
     * @param array $available_blocks_for_location
     * @return array
     */
    public function getOrder(Request $request, array $available_blocks_for_location): array;

}
