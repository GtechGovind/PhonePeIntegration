<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\Modules\Ticket\DashboardController;
use App\Http\Controllers\Modules\Ticket\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'index'])->name('home');
Route::post('/auth', [MainController::class, 'auth'])->name('auth');

// PRODUCTS
Route::get('/products', [ProductController::class, 'index'])->name('products');

// TICKET
Route::get('/ticket/dashboard', [DashboardController::class, 'index'])->name('ticket.dashboard');
Route::get('/ticket/order', [OrderController::class, 'index'])->name('ticket.index');
Route::post('/ticket/create', [OrderController::class, 'create'])->name('ticket.create');
Route::get('/ticket/order/pending', [OrderController::class, 'isPending'])->name('ticket.order.pending');
