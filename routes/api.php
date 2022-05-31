<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('jwt.verify')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); //
    Route::post('/refresh', [AuthController::class, 'refresh']); //
    Route::get('/user-profile', [UserController::class, 'getUserProfile']); //
    Route::get('/location', [LocationController::class, 'index']); //
    Route::post('/order/calculate/{location}', [OrderController::class, 'calculate']);
    Route::post('/order', [OrderController::class, 'store']);

});

Route::post('/login', [AuthController::class, 'login']); //

