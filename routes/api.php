<?php

use Illuminate\Http\Request;
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
Route::get('products', [ProductsModuleController::class, 'index']);
Route::post('products/create', [ProductsModuleController::class, 'store']);