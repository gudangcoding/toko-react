<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// API Routes
Route::prefix('v1')->group(function () {
    Route::get('/products', [\App\Http\Controllers\API\ProductController::class, 'index']);
    Route::get('/products/{id}', [\App\Http\Controllers\API\ProductController::class, 'show']);
});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
