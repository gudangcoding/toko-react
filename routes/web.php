<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('categories', App\Http\Controllers\CategoriesController::class)->only('index');

Route::resource('products', App\Http\Controllers\ProductController::class);

Route::resource('users', App\Http\Controllers\UserController::class);

Route::resource('orders', App\Http\Controllers\OrderController::class);


Route::resource('categories', App\Http\Controllers\CategoriesController::class)->only('index');

Route::resource('products', App\Http\Controllers\ProductController::class);

Route::resource('users', App\Http\Controllers\UserController::class);

Route::resource('orders', App\Http\Controllers\OrderController::class);


Route::resource('categories', App\Http\Controllers\CategoriesController::class)->only('index');

Route::resource('products', App\Http\Controllers\ProductController::class);

Route::resource('users', App\Http\Controllers\UserController::class);

Route::resource('orders', App\Http\Controllers\OrderController::class);
