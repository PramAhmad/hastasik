<?php

use App\Http\Controllers\ProductsModuleController;
use App\Http\Controllers\RajaOngkirApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// create product
Route::post('logout', function (Request $request) {
    auth()->user()->tokens->each(function ($token, $key) {
        $token->delete();
    });
    return response()->json(["message" => "success","data" => "logout success","status" => 200]);
});
Route::get('products', [ProductsModuleController::class, 'index']);
Route::get('products/{id}', [ProductsModuleController::class, 'show']);
Route::get('products/category/{category}', [ProductsModuleController::class, 'productByCategory']);
// Route::post('products/create', [ProductsModuleController::class, 'store']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("noauth", function(){
    return response()->json(["message" => "error","data" => "unauthorized","status" => 401]);
})->name("noauth");


// raja ongkir
Route::get('rajaongkir/province', [RajaOngkirApi::class, 'getProvince']);
Route::get('rajaongkir/city', [RajaOngkirApi::class, 'getCity']);
Route::post('rajaongkir/cost', [RajaOngkirApi::class, 'getCost']);