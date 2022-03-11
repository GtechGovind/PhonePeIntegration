<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'index'])->name('home');
Route::post('/auth', [MainController::class, 'auth'])->name('auth');

// PRODUCTS
Route::get('/products', [ProductController::class, 'index'])->name('products');
