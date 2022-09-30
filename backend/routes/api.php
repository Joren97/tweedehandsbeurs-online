<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EditionController;
use App\Http\Controllers\Api\ProductlistController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::post('/auth/register', [AuthController::class , 'register']);
Route::post('/auth/login', [AuthController::class , 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('productlist', ProductlistController::class);
    Route::apiResource('edition', EditionController::class);
});