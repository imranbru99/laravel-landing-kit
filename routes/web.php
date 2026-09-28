<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\LandingPageBuilderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderPdfController;
use App\Http\Controllers\OrderSuccessController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProductLandingController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// Home redirect to first active product or store welcome
Route::get('/', function () {
    $firstProduct = Product::active()->first();
    if ($firstProduct) {
        return redirect()->to('/' . $firstProduct->slug);
    }
    return view('welcome');
})->name('home');

// Checkout API routes (rate limited)
Route::prefix('api/checkout')->name('api.checkout.')->group(function () {
    Route::get('customer-lookup', [CheckoutController::class, 'customerLookup'])
        ->middleware('throttle:30,1')
        ->name('customer-lookup');
    Route::post('capture-lead', [CheckoutController::class, 'captureLead'])
        ->middleware('throttle:60,1')
        ->name('capture-lead');
});

// Location helper routes
Route::get('api/locations/thanas', [CheckoutController::class, 'thanas'])->name('api.locations.thanas');

// Public Checkout submit
Route::post('checkout/order', [CheckoutController::class, 'submitOrder'])
    ->middleware('throttle:10,1')
    ->name('checkout.order');

// Public Order Success page (signed/unguessable token)
Route::get('order-success/{token}', [OrderSuccessController::class, 'show'])->name('order.success');

// Public Order Tracking
Route::get('track-order', [OrderTrackingController::class, 'index'])->name('order.track');

// Admin PDF Generation routes
Route::prefix('admin/orders/{order}')->name('admin.orders.')->group(function () {
    Route::get('invoice', [OrderPdfController::class, 'invoice'])->name('invoice');
    Route::get('packing-slip', [OrderPdfController::class, 'packingSlip'])->name('packing-slip');
    Route::get('courier-sticker', [OrderPdfController::class, 'courierSticker'])->name('courier-sticker');
});

// Admin Landing Page Builder Routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('admin/products/{product}/builder', [LandingPageBuilderController::class, 'builderForProduct'])
        ->name('admin.products.builder');

    Route::prefix('admin/landing-pages/{landingPage}/builder')->name('admin.builder.')->group(function () {
        Route::get('/', [LandingPageBuilderController::class, 'builder'])->name('index');
        Route::get('canvas', [LandingPageBuilderController::class, 'canvas'])->name('canvas');
        Route::get('preview', [LandingPageBuilderController::class, 'preview'])->name('preview');
        Route::post('add-section', [LandingPageBuilderController::class, 'addSection'])->name('add-section');
        Route::post('reorder-sections', [LandingPageBuilderController::class, 'reorderSections'])->name('reorder-sections');
        Route::post('sections/{section}/update', [LandingPageBuilderController::class, 'updateSection'])->name('update-section');
        Route::post('sections/{section}/duplicate', [LandingPageBuilderController::class, 'duplicateSection'])->name('duplicate-section');
        Route::delete('sections/{section}', [LandingPageBuilderController::class, 'deleteSection'])->name('delete-section');
        Route::post('sections/{section}/toggle-visibility', [LandingPageBuilderController::class, 'toggleVisibility'])->name('toggle-visibility');
        Route::post('sections/{section}/save-reusable', [LandingPageBuilderController::class, 'saveAsReusable'])->name('save-reusable');
        Route::get('saved-sections', [LandingPageBuilderController::class, 'savedSections'])->name('saved-sections');
        Route::post('insert-saved-section', [LandingPageBuilderController::class, 'insertSavedSection'])->name('insert-saved-section');
        Route::post('create-revision', [LandingPageBuilderController::class, 'createRevision'])->name('create-revision');
        Route::post('revisions/{revision}/restore', [LandingPageBuilderController::class, 'restoreRevision'])->name('restore-revision');
        Route::post('update-settings', [LandingPageBuilderController::class, 'updatePageSettings'])->name('update-settings');
        Route::post('publish', [LandingPageBuilderController::class, 'publish'])->name('publish');
    });
});

// Universal root-level product landing page (placed last to avoid intercepting defined routes)
Route::get('{slug}', [ProductLandingController::class, 'show'])
    ->name('product.landing')
    ->where('slug', '[^\/]+');
