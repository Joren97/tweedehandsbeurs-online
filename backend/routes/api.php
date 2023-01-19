<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SeedController;
use App\Http\Controllers\Api\EditionController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductlistController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password/request', [AuthController::class, 'forgotPassword']);
Route::post('/auth/forgot-password/validate', [AuthController::class, 'validateForgotPasswordToken']);
Route::post('/auth/forgot-password/reset', [AuthController::class, 'resetPassword']);
Route::get('/mail', [SeedController::class, 'testMail']);

// User routes
Route::group(
    ['middleware' => ['auth:sanctum', 'ability:user,employee,admin']],
    function () {
        Route::get('auth/userinfo', [AuthController::class, 'userinfo']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::put('auth/me', [AuthController::class, 'updateProfile']);

        Route::get('productlist/me', [ProductlistController::class, 'getListsForLoggedInUser']);
        Route::post('productlist/me', [ProductlistController::class, 'storeForLoggedInUser']);
        Route::put('productlist/me/confirm/{productlist}', [ProductlistController::class, 'confirmListForLoggedInUser']);
        Route::get('productlist/me/{productlist}', [ProductlistController::class, 'showForLoggedInUser']);
        Route::delete('productlist/me/{productlist}', [ProductlistController::class, 'destroyForLoggedInUser']);

        Route::post('product/me', [ProductController::class, 'storeForLoggedInUser']);
        Route::delete('product/me/{product}', [ProductController::class, 'destroyForLoggedInUser']);
        Route::put('product/me/{product}', [ProductController::class, 'updateForLoggedInUser']);

        Route::get('price', [PriceController::class, 'index']);

        Route::get('edition', [EditionController::class, 'index']);
        Route::get('edition/{edition}', [EditionController::class, 'show']);
    }
);

// Employee routes
Route::group(
    ['middleware' => ['auth:sanctum', 'ability:employee,admin']],
    function () {
        Route::apiResource('productlist', ProductlistController::class);
        Route::apiResource('product', ProductController::class);
        Route::apiResource('user', UserController::class);
        Route::post('productlist/pay', [ProductlistController::class, 'payProductlists']);
    }
);

// Admin routes
Route::group(
    ['middleware' => ['auth:sanctum', 'ability:admin']],
    function () {
        Route::post('/seed', [SeedController::class, 'seed']);
        Route::post('/seedPrices', [SeedController::class, 'seedPrices']);
        Route::post('/clear', [SeedController::class, 'clear']);

        Route::post('price', [PriceController::class, 'store']);
        Route::put('price/{price}', [PriceController::class, 'update']);
        Route::delete('price/{price}', [PriceController::class, 'destroy']);

        Route::post('edition', [EditionController::class, 'store']);
        Route::put('edition/{edition}', [EditionController::class, 'update']);
        Route::delete('edition/{edition}', [EditionController::class, 'destroy']);
    }
);