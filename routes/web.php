<?php

use App\Http\Controllers\OrderPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/orders/{order}')->name('admin.orders.')->group(function () {
    Route::get('invoice', [OrderPdfController::class, 'invoice'])->name('invoice');
    Route::get('packing-slip', [OrderPdfController::class, 'packingSlip'])->name('packing-slip');
    Route::get('courier-sticker', [OrderPdfController::class, 'courierSticker'])->name('courier-sticker');
});
