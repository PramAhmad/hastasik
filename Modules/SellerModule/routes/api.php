<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SellerModule\App\Http\Controllers\AuthSellerController;

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