<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCalculationRequest;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Services\PriceCalculationService\PriceCalculationServiceInterface;
use App\Services\WriteOperationServices\OrderWriteOperationService\OrderWriteOperationServiceInterface;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderController extends Controller
{

    protected $locationRepository;
    protected $priceCalculationService;
    protected $orderWriteOperationService;

    public function __construct(LocationRepositoryInterface         $locationRepository,
                                PriceCalculationServiceInterface    $priceCalculationService,
                                OrderWriteOperationServiceInterface $orderWriteOperationService)
    {
        $this->locationRepository = $locationRepository;
        $this->priceCalculationService = $priceCalculationService;
        $this->orderWriteOperationService = $orderWriteOperationService;
    }

    /**
     *
     * @OA\Post(
     *     path="/api/order",
     *     operationId="CreateOrder",
     *     tags = {"Orders"},
     *     security={ {"bearer": {} }},
     *     summary = "calculate new order",
     *     description = "Check for availability of needed blocks quantity and give price",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"temperature", "start_time", "finish_time", "capacity", "location_id"},
     *               @OA\Property(property="start_time", type="datatime", example="2022-06-11 21:36:35"),
     *               @OA\Property(property="finish_time", type="datatime", example="2022-06-11 21:36:35"),
     *               @OA\Property(property="temperature", type="int", example=-10),
     *               @OA\Property(property="capacity", type="int", example=30),
     *               @OA\Property(property="location_id", type="int", example=1),
     *            ),
     *        ),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfull.",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="code",
     *                  type="string",
     *                  readOnly="true",
     *                  example="AAAAAAAAAAAA"),
     *               @OA\Property(
     *                   property="capacity",
     *                   type="integer",
     *                   readOnly="true",
     *                   example=40),
     *              @OA\Property(
     *                   property="price",
     *                   type="float",
     *                   readOnly="true",
     *                   example=650.5),
     *              @OA\Property(
     *                   property="debt",
     *                   type="float",
     *                   readOnly="true",
     *                   example=650.5),
     *              @OA\Property(
     *                   property="start_time",
     *                   type="datetime",
     *                   example="2022-06-11 21:36:35"),
     *              @OA\Property(
     *                   property="finish_time",
     *                   type="datetime",
     *                    example="2022-06-11 21:36:35"),
     *               @OA\Property(
     *                   property="user_id",
     *                   type="integer",
     *                   example=1),
     *              @OA\Property(
     *                   property="id",
     *                   type="integer",
     *                   example=90),
     *     )),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="validation errors."
     *     )
     * );
     *
     * @param OrderStoreRequest $request
     * @return mixed
     */
    public function store(OrderStoreRequest $request)
    {
        $location = Location::find($request->location_id);
        $blocks_in_rooms_to_order = $this->priceCalculationService->getOrder($request,
            $this->locationRepository->getAvailableBlocksForLocation($request, $location));

        $blocks_price = $this->priceCalculationService->getPriceAndBlocksQuantity($request, $location->id,
            $this->locationRepository->getAvailableBlocksForLocation($request, $location));

        if ($blocks_price['price'] == 0) {
            throw new HttpResponseException(response()->json('No blocks', '200'));
        }
        return $this->orderWriteOperationService->store($request, $blocks_in_rooms_to_order, $blocks_price);
    }


    /**
     * @OA\Post(
     *     path="/api/order/calculate/{location_id}",
     *     operationId="CalculateOrder",
     *     tags = {"Orders"},
     *     summary = "calculate new order",
     *     description = "Check for availability of needed blocks quantity and give price",
     *     @OA\Parameter(
     *         name="location_id",
     *         in="path",
     *         description="id of needed location",
     *         required=true,
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"temperature", "start_time", "finish_time", "capacity"},
     *               @OA\Property(property="start_time", type="datatime", example="2022-06-11 21:36:35"),
     *               @OA\Property(property="finish_time", type="datatime", example="2022-06-11 21:36:35"),
     *               @OA\Property(property="temperature", type="int", example=-10),
     *               @OA\Property(property="capacity", type="int", example=30),
     *            ),
     *        ),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfull.",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="blocks_quantity",
     *                  type="integer",
     *                  readOnly="true",
     *                  example="50"),
     *               @OA\Property(
     *                   property="price",
     *                   type="integer",
     *                   readOnly="true",
     *                   example=450)
     *     )),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="validation errors."
     *     ),
     *      security={ {"bearer": {} }},
     * );
     *
     * @param OrderCalculationRequest $request
     * @param Location $location
     * @return array
     */
    public function calculate(OrderCalculationRequest $request, Location $location): array
    {
        $available_blocks_for_location = $this->locationRepository
            ->getAvailableBlocksForLocation($request, $location);
        $blocks_price = $this->priceCalculationService->getPriceAndBlocksQuantity($request, $location,
            $available_blocks_for_location);

        if ($blocks_price['price'] == 0) {
            throw new HttpResponseException(response()->json('No blocks', 200));
        }

        return $blocks_price;
    }

}
