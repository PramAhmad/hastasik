<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\ProductsModule\App\Http\Controllers\ProductsModuleController;

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
Route::get('products', [ProductsModuleController::class, 'index'])->middleware(['auth','seller']);
Route::post('products/create', [ProductsModuleController::class, 'store']);

Route::get("noauth", function(){
    return response()->json(["message" => "error","data" => "unauthorized","status" => 401]);
})->name("noauth");