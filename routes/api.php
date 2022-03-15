<?php

use App\Http\Controllers\Api\MMOPL\FareController;
use App\Http\Controllers\Api\Settlement\SettlementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/get/fare', [FareController::class, 'getFare'])->name('fare');

// SETTLEMENT
Route::middleware(['basic_auth'])->group(function () {
    Route::post('get/settlement/issue', [SettlementController::class, 'getIssueUnsettledData'])->name('settlement.issue');
    Route::post('get/settlement/refund', [SettlementController::class, 'getRefundUnsettledData'])->name('settlement.refund');
    Route::post('set/settlement/issue', [SettlementController::class, 'setIssueUnsettledData'])->name('settlement.post.issue');
    Route::post('set/settlement/refund', [SettlementController::class, 'setRefundUnsettledData'])->name('settlement.post.refund');
});
