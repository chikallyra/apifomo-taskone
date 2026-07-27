<?php

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [APIController::class, 'index']);
Route::post('/products', [APIController::class, 'store_product']);
Route::post('/orders', [APIController::class, 'checkout']);
