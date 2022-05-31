<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     tags = {"Auth"},
     *     summary = "Login",
     *     description = "Get user information with orders and its information",
     *      @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *         response=200,
     *         description="successful.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="access_token",
     *                  type="string",
     *                  readOnly="true",
     *                  example="your_access_token"),
     *             @OA\Property(
     *                   property="expires_in",
     *                   type="integer",
     *                   readOnly="true",
     *                   example=86400),
     *             @OA\Property(
     *                   property="token_type",
     *                   type="string",
     *                   readOnly="true",
     *                   example="bearer"),
     *             @OA\Property(
     *                  property = "user",
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
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      readOnly="true",
     *                      example="User Name"),
     *                  @OA\Property(
     *                      property="start_time",
     *                      type="datetime",
     *                      example="2022-06-11 21:36:35" ),
     *                  @OA\Property(
     *                      property="finish_time",
     *                      type="datetime",
     *                      example="2022-06-11 21:36:35"),
     *     ))),
     *
     *      @OA\Response(
     *         response=422,
     *         description="validation errors."
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     ),

     * ),
     */
    public function login(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->all())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }



    /**
     *     @OA\Post(
     *     path="/api/logout",
     *     operationId="logout",
     *     tags = {"Auth"},
     *     security={ {"bearer": {} }},
     *     summary = "Logout",
     *     description = "LogOut from the system",
     *
     *      @OA\Response(
     *         response=200,
     *         description="successful.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="access_token",
     *                  type="string",
     *                  readOnly="true",
     *                  example="your_access_token"),
     *             @OA\Property(
     *                   property="expires_in",
     *                   type="integer",
     *                   readOnly="true",
     *                   example=86400),
     *             @OA\Property(
     *                   property="token_type",
     *                   type="string",
     *                   readOnly="true",
     *                   example="bearer"),
     *             @OA\Property(
     *                  property = "user",
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
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      readOnly="true",
     *                      example="User Name"),
     *                  @OA\Property(
     *                      property="start_time",
     *                      type="datetime",
     *                      example="2022-06-11 21:36:35" ),
     *                  @OA\Property(
     *                      property="finish_time",
     *                      type="datetime",
     *                      example="2022-06-11 21:36:35"),
     *     ))),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *
     * ),
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }




    /**
     *    @OA\Post(
     *     path="/api/refresh",
     *     operationId="refresh",
     *     tags = {"Auth"},
     *     security={ {"bearer": {} }},
     *     summary = "Refresh",
     *     description = "Refresh users's access token",
     *
     *      @OA\Response(
     *         response=200,
     *         description="successful.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                  property="access_token",
     *                  type="string",
     *                  readOnly="true",
     *                  example="your_access_token"),
     *             @OA\Property(
     *                   property="expires_in",
     *                   type="integer",
     *                   readOnly="true",
     *                   example=86400),
     *             @OA\Property(
     *                   property="token_type",
     *                   type="string",
     *                   readOnly="true",
     *                   example="bearer"),
     *             @OA\Property(
     *                  property = "user",
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
     *                   @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      readOnly="true",
     *                      example="User Name"),
     *                  @OA\Property(
     *                      property="start_time",
     *                      type="datetime",
     *                      example="2022-06-11 21:36:35" ),
     *                  @OA\Property(
     *                      property="finish_time",
     *                      type="datetime",
     *                      example="2022-06-11 21:36:35"),
     *     ))),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *
     * ),
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }


    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 1440,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ]);
    }

}
