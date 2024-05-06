<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SellerModule\App\Http\Controllers\AuthSellerController;
use Modules\SellerModule\App\Http\Controllers\ProductSellerController;
use Modules\SellerModule\App\Http\Controllers\SellerModuleController;
use Modules\SellerModule\App\Http\Controllers\UlasanSellerController;

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

// create seller
Route::post('/seller/register', [AuthSellerController::class, 'RegisterSeller']);
Route::post('/seller/login', [AuthSellerController::class, 'LoginSeller']);

// routegroup middlewreseller
Route::middleware(['auth:sanctum', 'seller'])->group(function () {
    Route::get('/seller', [AuthSellerController::class, 'ShowSeller']);
    Route::post('/seller/update', [AuthSellerController::class, 'UpdateDataSeller']);
    Route::post('/seller/logout', [AuthSellerController::class, 'LogoutSeller']);
});

// route seller
Route::middleware(['auth:sanctum', 'seller'])->group(function () {
    Route::get('/seller/products', [ProductSellerController::class, 'index']);
    Route::post('/seller/products/create', [ProductSellerController::class, 'store']);
    Route::get('/seller/products/{id}', [ProductSellerController::class, 'show']);
    Route::post('/seller/products/{id}', [ProductSellerController::class, 'update']);
    Route::delete('/seller/products/{id}', [ProductSellerController::class, 'destroy']);
    // route product by seller id
    Route::get('/products/seller/{id}', [ProductSellerController::class, 'productBySeller']);
    
    // route ulasan
    Route::get("/seller/reviews", [UlasanSellerController::class, 'getReviewsBySeller']);
});

// route seller info
Route::get("/seller/info/{id}", [SellerModuleController::class, 'getSellerInfobyId']);


