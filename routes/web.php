<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\Modules\GateRejection\GraController;
use App\Http\Controllers\Modules\Processing\ProcessingController;
use App\Http\Controllers\Modules\Refund\RefundController;
use App\Http\Controllers\Modules\StoreValue\StoreValueDashboardController;
use App\Http\Controllers\Modules\StoreValue\StoreValueOrderController;
use App\Http\Controllers\Modules\StoreValue\StoreValueReloadController;
use App\Http\Controllers\Modules\StoreValue\StoreValueStatusController;
use App\Http\Controllers\Modules\Ticket\DashboardController;
use App\Http\Controllers\Modules\Ticket\OrderController;
use App\Http\Controllers\Modules\Ticket\TicketStatusController;
use App\Http\Controllers\Modules\Ticket\TicketViewController;
use App\Http\Controllers\Modules\TripPass\TripPassDashboardController;
use App\Http\Controllers\Modules\TripPass\TripPassOrderController;
use App\Http\Controllers\Modules\TripPass\TripPassReloadController;
use App\Http\Controllers\Modules\TripPass\TripPassStatusController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('home');
Route::post('auth', [MainController::class, 'auth'])->name('auth');

// PRODUCTS
Route::get('products', [ProductController::class, 'index'])->name('products');

// PROCESSING TICKET
Route::get('processing/{order_id}', [ProcessingController::class, 'index'])->name('processing');
Route::get('processing/init/{order_id}', [ProcessingController::class, 'init'])->name('processing.init');
Route::get('processing/failed/{order_id}', [ProcessingController::class, 'failed'])->name('processing.failed');

// TICKET
Route::get('ticket/dashboard', [DashboardController::class, 'index'])->name('ticket.dashboard');
Route::get('ticket/order', [OrderController::class, 'index'])->name('ticket.index');
Route::get('ticket/status', [TicketStatusController::class, 'index'])->name('ticket.status');
Route::post('ticket/create', [OrderController::class, 'create'])->name('ticket.create');
Route::get('ticket/order/pending', [OrderController::class, 'isPending'])->name('ticket.order.pending');
Route::get('ticket/view/{order_id}', [TicketViewController::class, 'index'])->name('ticket.view');

// STORE VALUE
Route::get('sv/dashboard', [StoreValueDashboardController::class, 'index'])->name('sv.dashboard');
Route::get('sv/canIssuePass', [StoreValueOrderController::class, 'canIssuePass'])->name('sv.canIssuePass');
Route::get('sv/order', [StoreValueOrderController::class, 'index'])->name('sv.order');
Route::post('sv/create', [StoreValueOrderController::class, 'create'])->name('sv.create');
Route::get('sv/trip/{order_id}', [StoreValueOrderController::class, 'issueTrip'])->name('sv.issueTrip');
Route::get('sv/status/{master_id}', [StoreValueStatusController::class, 'index'])->name('sv.status');
Route::get('sv/reload/status/{order_id}', [StoreValueReloadController::class, 'status'])->name('sv.reload.status');
Route::get('sv/reload/{order_id}', [StoreValueReloadController::class, 'index'])->name('sv.reload.index');
Route::post('sv/reload', [StoreValueReloadController::class, 'reload'])->name('sv.reload');

// TRIP PASS VALUE
Route::get('tp/dashboard', [TripPassDashboardController::class, 'index'])->name('tp.dashboard');
Route::get('tp/canIssuePass', [StoreValueOrderController::class, 'canIssuePass'])->name('tp.canIssuePass');
Route::get('tp/order', [TripPassOrderController::class, 'index'])->name('tp.order');
Route::post('tp/create', [TripPassOrderController::class, 'create'])->name('tp.create');
Route::get('tp/trip/{order_id}', [TripPassOrderController::class, 'issueTrip'])->name('tp.issueTrip');
Route::get('tp/status/{master_id}', [TripPassStatusController::class, 'index'])->name('tp.status');
Route::get('tp/reload/status/{order_id}', [TripPassReloadController::class, 'status'])->name('tp.reload.status');
Route::get('tp/reload/{order_id}', [TripPassReloadController::class, 'index'])->name('tp.reload.index');
Route::post('tp/reload', [TripPassReloadController::class, 'reload'])->name('tp.reload');

// GRA
Route::get('gra/{slave_id}/{station_id}', [GraController::class, 'info'])->name('gra.info');
Route::post('gra', [GraController::class, 'apply'])->name('gra.perform-gra');

// REFUND
Route::get('refund/{order_id}', [RefundController::class, 'info'])->name('refund.info');
Route::get('refund/ticket/{order_id}', [RefundController::class, 'apply'])->name('refund');
