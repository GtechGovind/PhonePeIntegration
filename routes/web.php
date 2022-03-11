<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\Modules\Ticket\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'index'])->name('home');
Route::post('/auth', [MainController::class, 'auth'])->name('auth');

// PRODUCTS
Route::get('/products', [ProductController::class, 'index'])->name('products');

// TICKET
Route::get('/ticket/dashboard', [DashboardController::class, 'index'])->name('ticket.dashboard');
