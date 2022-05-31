<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;
    protected $user;


    public function __construct(UserRepositoryInterface $userRepository, LocationRepositoryInterface $locationRepository)
    {
        $this->userRepository = $userRepository;
        $this->user = auth()->user();
    }

    /**
     * @OA\Get(
     *     path="/api/user-profile",
     *     operationId="getUserProfile",
     *     tags = {"Users"},
     *     security={ {"bearer": {} }},
     *     summary = "Get user information",
     *     description = "Get user information with orders and its information",
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *           @OA\JsonContent(
     *              @OA\Property(
     *                  property = "data",
     *                  type = "object",
     *                  @OA\Property(
     *                      property="id",
     *                      type="integer",
     *                      readOnly="true",
     *                      example=1),
     *                   @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      readOnly="true",
     *                      format="email",
     *                      description="User unique email address",
     *                      example="user@gmail.com"),
     *                  @OA\Property(
     *                      property="orders",
     *                      type="array",
     *                      example = {{
     *                          "id" : 1,
     *                          "code": "AAAAAAAAAAAA",
     *                          "start_time" : "2022-06-11 21:36:35",
     *                          "finish_time" : "2022-06-11 21:36:35",
     *                          "status_id" : 3,
     *                          "status" : "cancelled",
     *                      }},
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example= 1
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              type="string",
     *                              example="AAAAAAAAAAAA"
     *                          ),
     *                          @OA\Property(
     *                              property="start_time",
     *                              type="datetime",
     *                              example="2022-06-11 21:36:35"
     *                          ),
     *                          @OA\Property(
     *                              property="finish_time",
     *                              type="datetime",
     *                              example="2022-06-11 21:36:35"
     *                          ),
     *                          @OA\Property(
     *                              property="status_id",
     *                              type="integer",
     *                              example = 3,
     *                           ),
     *                          @OA\Property(
     *                              property="status",
     *                              type="string",
     *                              example = "cancelled",
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
     */

    public function getUserProfile()
    {
        return new UserProfileResource($this->user);
    }



}
