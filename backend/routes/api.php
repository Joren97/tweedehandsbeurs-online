<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SeedController;
use App\Http\Controllers\Api\EditionController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductlistController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::post('/auth/register', [AuthController::class , 'register']);
Route::post('/auth/login', [AuthController::class , 'login']);

// TODO PROTECT ROUTES USING SANCTUM ROLES
// See https://stackoverflow.com/questions/71358904/laravel-sanctum-multiple-guard-middleware
Route::get('auth/userinfo', [AuthController::class , 'userinfo']);
Route::apiResource('productlist', ProductlistController::class);
Route::apiResource('edition', EditionController::class);
Route::apiResource('price', PriceController::class);
Route::apiResource('product', ProductController::class);


// TODO DELETE THIS WHEN GOING IN PRODUCTION
Route::post('/seed', [SeedController::class , 'seed']);
Route::post('/clear', [SeedController::class , 'clear']);