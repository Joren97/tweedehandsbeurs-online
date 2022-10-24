<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SeedController;
use App\Http\Controllers\Api\EditionController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductlistController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// User routes
Route::group(['middleware' => ['auth:sanctum', 'ability:user,employee,admin']], function () {
    Route::get('auth/userinfo', [AuthController::class, 'userinfo']);

    Route::get('productlist/me', [ProductlistController::class, 'getListsForLoggedInUser']);

    Route::get('edition', [EditionController::class, 'index']);
    Route::get('edition/{edition}', [EditionController::class, 'show']);
});

// Employee routes
Route::group(['middleware' => ['auth:sanctum', 'ability:employee,admin']], function () {
    Route::apiResource('productlist', ProductlistController::class);

    Route::post('edition', [EditionController::class, 'store']);
    Route::put('edition/{edition}', [EditionController::class, 'update']);
    Route::delete('edition/{edition}', [EditionController::class, 'destroy']);

    Route::apiResource('price', PriceController::class);
    Route::apiResource('product', ProductController::class);
});

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    Route::post('/seed', [SeedController::class, 'seed']);
    Route::post('/clear', [SeedController::class, 'clear']);
});