<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\Modules\Processing\ProcessingController;
use App\Http\Controllers\Modules\Ticket\DashboardController;
use App\Http\Controllers\Modules\Ticket\OrderController;
use App\Http\Controllers\Modules\Ticket\TicketViewController;
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
Route::get('/ticket/view/{order_id}', [TicketViewController::class, 'index'])->name('ticket.view');


// PROCESSING TICKET
Route::get('/processing/{order_id}', [ProcessingController::class, 'index'])->name('processing');
Route::get('/processing/init/{order_id}', [ProcessingController::class, 'init'])->name('processing.init');
Route::get('/processing/failed/{order_id}', [ProcessingController::class, 'failed'])->name('processing.failed');
