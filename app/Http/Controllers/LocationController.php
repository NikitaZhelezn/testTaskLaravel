<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Services\PriceCalculationService\PriceCalculationServiceInterface;

class LocationController extends Controller
{

    protected $locationRepository;
    protected $priceCalculationService;

    public function __construct(LocationRepositoryInterface $locationRepository,
                                PriceCalculationServiceInterface $priceCalculationService)
    {
        $this->locationRepository = $locationRepository;
        $this->priceCalculationService = $priceCalculationService;
    }

    /**
     * @OA\Get(
     *     path="/api/location",
     *     operationId="getBlocksQuantityPerLocation",
     *     tags = {"Location"},
     *     security={ {"bearer": {} }},
     *     summary = "Get blocks quantity per location",
     *     description = "Get blocks quantity in general and available qyantity per location",
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *           @OA\JsonContent(
     *                  @OA\Property(
     *                      property="locations",
     *                      type="array",
     *                      example = {{
     *                          "location_id" : 1,
     *                          "location_city": "Wilmington",
     *                          "location_blocks_quantity" : "184",
     *                          "location_blocks_available_quantity" : "184",
     *                      }},
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="location_id",
     *                              type="integer",
     *                              example= 1
     *                          ),
     *                          @OA\Property(
     *                              property="location_city",
     *                              type="string",
     *                              example="Wilmington"
     *                          ),
     *                          @OA\Property(
     *                              property="location_blocks_quantity",
     *                              type="integer",
     *                              example=184
     *                          ),
     *                          @OA\Property(
     *                              property="location_blocks_available_quantity",
     *                              type="integer",
     *                              example=184
     *                          ),
     *                          )
     *                      )))
     * )
     *),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *
     *      @OA\Response(
     *         response=422,
     *         description="validation errors."
     *     ),
     * );
     *
     * @return array
     */
    public function index()
    {
        return $this->locationRepository->getAvailableBlocksList();
    }

}
