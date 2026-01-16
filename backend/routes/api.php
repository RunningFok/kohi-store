<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\BasketController;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::post('/customers/register', [CustomerController::class, 'register']);
Route::post('/customers/login', [CustomerController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customers/me', [CustomerController::class, 'me']);
    Route::post('/customers/logout', [CustomerController::class, 'logout']);
    
    Route::get('/basket', [BasketController::class, 'get']);
    Route::post('/basket', [BasketController::class, 'save']);
    Route::delete('/basket', [BasketController::class, 'clear']);
});
