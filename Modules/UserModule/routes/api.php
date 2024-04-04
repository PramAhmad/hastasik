<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('usermodule', fn (Request $request) => $request->user())->name('usermodule');
});

Route::prefix('/customer')->name('api.customer.')->group(function () {
    Route::post('register', 'Modules\UserModule\App\Http\Controllers\AuthUserController@RegisterUser')->name('register');
    Route::post('login', 'Modules\UserModule\App\Http\Controllers\AuthUserController@LoginUser')->name('login');
    Route::post('logout', 'Modules\UserModule\App\Http\Controllers\AuthUserController@LogoutUser')->name('logout');
});
// alamat
Route::middleware(['auth:sanctum','customer'])->group(function () {
    Route::get('/customer/alamat', 'Modules\UserModule\App\Http\Controllers\AlamatCustomerController@index');
    Route::get('/customer/alamat/{id}', 'Modules\UserModule\App\Http\Controllers\AlamatCustomerController@show');
    Route::post('/customer/alamat', 'Modules\UserModule\App\Http\Controllers\AlamatCustomerController@store');
    Route::post('/customer/alamat/{id}', 'Modules\UserModule\App\Http\Controllers\AlamatCustomerController@update');
    Route::delete('/customer/alamat/{id}', 'Modules\UserModule\App\Http\Controllers\AlamatCustomerController@destroy');
});

// review
Route::middleware(['auth:sanctum','customer'])->group(function () {
    Route::post('/customer/review', 'Modules\UserModule\App\Http\Controllers\ReviewUserController@PostReview');
});