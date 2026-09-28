<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Exceptions\InvalidStatusTransitionException;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Thana;
use App\Services\Courier\CourierManager;
use App\Services\Courier\ManualCourierDriver;
use App\Services\Courier\SteadfastCourierDriver;
use App\Services\OrderStatusStateMachine;
use App\Services\PdfGeneratorService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

test('order generates unique token and formatted order number on creation', function () {
    $district = District::first();
    $thana = Thana::where('district_id', $district->id)->first();

    $customer = Customer::create([
        'phone' => '01712345678',
        'name' => 'Abdur Rahman',
        'default_address' => 'House 12, Road 4, Dhanmondi',
        'district_id' => $district->id,
        'thana_id' => $thana->id,
    ]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01712345678',
        'customer_name' => 'Abdur Rahman',
        'customer_address' => 'House 12, Road 4, Dhanmondi',
        'district_id' => $district->id,
        'thana_id' => $thana->id,
        'status' => OrderStatus::Pending,
        'subtotal' => 1500.00,
        'delivery_charge' => 60.00,
        'total_amount' => 1560.00,
        'payment_method' => 'cod',
    ]);

    expect($order->order_number)->toBeString()->toStartWith('ORD-')
        ->and($order->order_token)->toBeString()->toHaveLength(40);
});

test('order status state machine allows valid transitions and decrements stock on confirmation', function () {
    Event::fake([OrderStatusChanged::class]);

    $district = District::first();
    $product = Product::create([
        'name' => 'Organic Honey',
        'slug' => 'organic-honey-phase3',
        'regular_price' => 800.00,
        'stock_quantity' => 20,
        'track_stock' => true,
    ]);

    $customer = Customer::create([
        'phone' => '01812345678',
        'name' => 'Karim Ullah',
        'district_id' => $district->id,
    ]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01812345678',
        'customer_name' => 'Karim Ullah',
        'customer_address' => 'Uttara Sector 3',
        'district_id' => $district->id,
        'status' => OrderStatus::Pending,
        'subtotal' => 1600.00,
        'delivery_charge' => 60.00,
        'total_amount' => 1660.00,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'unit_price' => 800.00,
        'quantity' => 2,
        'total_price' => 1600.00,
    ]);

    $stateMachine = app(OrderStatusStateMachine::class);

    // Transition from PENDING to CONFIRMED
    $updatedOrder = $stateMachine->transition($order, OrderStatus::Confirmed, null, 'Confirmed by agent');

    expect($updatedOrder->status)->toBe(OrderStatus::Confirmed);
    expect($product->fresh()->stock_quantity)->toBe(18); // 20 - 2

    // Verify history was logged
    expect($updatedOrder->statusHistories)->toHaveCount(1)
        ->and($updatedOrder->statusHistories->first()->to_status)->toBe(OrderStatus::Confirmed);

    Event::assertDispatched(OrderStatusChanged::class);
});

test('order status state machine prevents invalid transitions and restores stock on cancellation', function () {
    $district = District::first();
    $product = Product::create([
        'name' => 'Premium Panjabi',
        'slug' => 'premium-panjabi-phase3',
        'regular_price' => 2500.00,
        'stock_quantity' => 10,
        'track_stock' => true,
    ]);

    $customer = Customer::create([
        'phone' => '01912345678',
        'name' => 'Rafiqul Islam',
        'district_id' => $district->id,
    ]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01912345678',
        'customer_name' => 'Rafiqul Islam',
        'customer_address' => 'Mirpur 10',
        'district_id' => $district->id,
        'status' => OrderStatus::Pending,
        'subtotal' => 2500.00,
        'delivery_charge' => 60.00,
        'total_amount' => 2560.00,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'unit_price' => 2500.00,
        'quantity' => 1,
        'total_price' => 2500.00,
    ]);

    $stateMachine = app(OrderStatusStateMachine::class);

    // Confirm order (reduces stock to 9)
    $stateMachine->transition($order, OrderStatus::Confirmed);
    expect($product->fresh()->stock_quantity)->toBe(9);

    // Try invalid transition from CONFIRMED directly to RETURN_RECEIVED (should fail)
    expect(fn () => $stateMachine->transition($order, OrderStatus::ReturnReceived))
        ->toThrow(InvalidStatusTransitionException::class);

    // Cancel order -> restores stock to 10
    $stateMachine->transition($order, OrderStatus::Cancelled, null, 'Customer requested cancellation');
    expect($product->fresh()->stock_quantity)->toBe(10);
});

test('customer metrics update correctly when order is delivered', function () {
    $district = District::first();
    $customer = Customer::create([
        'phone' => '01312345678',
        'name' => 'Nusrat Jahan',
        'district_id' => $district->id,
        'total_orders' => 0,
        'total_spent' => 0,
        'delivered_orders_count' => 0,
    ]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01312345678',
        'customer_name' => 'Nusrat Jahan',
        'customer_address' => 'Gulshan 2',
        'district_id' => $district->id,
        'status' => OrderStatus::Pending,
        'subtotal' => 3000.00,
        'delivery_charge' => 60.00,
        'total_amount' => 3060.00,
    ]);

    $stateMachine = app(OrderStatusStateMachine::class);
    $stateMachine->transition($order, OrderStatus::Confirmed);
    $stateMachine->transition($order, OrderStatus::Processing);
    $stateMachine->transition($order, OrderStatus::ReadyToShip);
    $stateMachine->transition($order, OrderStatus::Shipped);
    $stateMachine->transition($order, OrderStatus::Delivered);

    $customer->refresh();
    expect($customer->total_orders)->toBe(1)
        ->and((float) $customer->total_spent)->toBe(3060.00)
        ->and($customer->delivered_orders_count)->toBe(1)
        ->and((float) $customer->success_rate)->toBe(100.0);
});

test('pdf generator renders unicode bangla invoice and packing slip templates', function () {
    $district = District::first();
    $product = Product::create([
        'name' => 'খাঁটি সরিষার তেল (Pure Mustard Oil)',
        'slug' => 'pure-mustard-oil-phase3',
        'regular_price' => 450.00,
    ]);

    $customer = Customer::create([
        'phone' => '01512345678',
        'name' => 'আব্দুল করিম (Abdul Karim)',
        'district_id' => $district->id,
    ]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01512345678',
        'customer_name' => 'আব্দুল করিম (Abdul Karim)',
        'customer_address' => 'বাড়ি ১২, রোড ৪, ধানমন্ডি',
        'district_id' => $district->id,
        'status' => OrderStatus::Confirmed,
        'subtotal' => 900.00,
        'delivery_charge' => 60.00,
        'total_amount' => 960.00,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'unit_price' => 450.00,
        'quantity' => 2,
        'total_price' => 900.00,
    ]);

    $pdfService = app(PdfGeneratorService::class);

    // Verify invoice HTML renders with Bangla text correctly
    $invoiceHtml = view('pdf.invoice', ['order' => $order->load(['customer', 'items.product', 'district', 'thana'])])->render();
    expect($invoiceHtml)->toContain('আব্দুল করিম')
        ->and($invoiceHtml)->toContain('ধানমন্ডি')
        ->and($invoiceHtml)->toContain('Invoice');

    // Verify packing slip HTML renders
    $slipHtml = view('pdf.packing-slip', ['order' => $order->load(['customer', 'items.product', 'district', 'thana'])])->render();
    expect($slipHtml)->toContain('Packing Slip')
        ->and($slipHtml)->toContain('খাঁটি সরিষার তেল');

    // Verify courier sticker HTML renders
    $stickerHtml = view('pdf.courier-sticker', ['order' => $order->load(['customer', 'items.product', 'district', 'thana'])])->render();
    expect($stickerHtml)->toContain('DELIVER TO')
        ->and($stickerHtml)->toContain('01512345678');
});

test('courier drivers create shipments and track consignments', function () {
    $district = District::first();
    $customer = Customer::create([
        'phone' => '01612345678',
        'name' => 'Tareq Mahmud',
        'district_id' => $district->id,
    ]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01612345678',
        'customer_name' => 'Tareq Mahmud',
        'customer_address' => 'Agrabad, Chittagong',
        'district_id' => $district->id,
        'status' => OrderStatus::ReadyToShip,
        'subtotal' => 1200.00,
        'delivery_charge' => 120.00,
        'total_amount' => 1320.00,
    ]);

    // Test Manual Courier Driver
    $manualDriver = new ManualCourierDriver();
    $result = $manualDriver->sendOrder($order);

    expect($result->success)->toBeTrue()
        ->and($result->consignmentId)->toStartWith('MANUAL-');

    // Test Steadfast Courier Driver with mocked HTTP response
    Http::fake([
        'https://portal.steadfast.com.bd/api/v1/create_order' => Http::response([
            'status' => 200,
            'consignment' => [
                'consignment_id' => 987654321,
                'tracking_code' => 'STDF-987654321',
            ],
        ], 200),
        'https://portal.steadfast.com.bd/api/v1/status_by_cid/987654321' => Http::response([
            'status' => 200,
            'delivery_status' => 'delivered',
        ], 200),
    ]);

    \App\Models\Setting::updateOrCreate(['key' => 'steadfast_api_key'], ['value' => 'test-api-key', 'group' => 'courier', 'type' => 'string']);
    \App\Models\Setting::updateOrCreate(['key' => 'steadfast_secret_key'], ['value' => 'test-secret-key', 'group' => 'courier', 'type' => 'string']);
    app(\App\Services\SettingService::class)->clearCache();

    $steadfastDriver = app(SteadfastCourierDriver::class);
    $sfResult = $steadfastDriver->sendOrder($order);

    expect($sfResult->success)->toBeTrue()
        ->and($sfResult->consignmentId)->toBe('987654321')
        ->and($sfResult->trackingCode)->toBe('STDF-987654321');

    $order->update(['courier_consignment_id' => '987654321']);
    $tracking = $steadfastDriver->trackOrder($order);
    expect($tracking->status)->toBe('delivered')
        ->and($tracking->isDelivered())->toBeTrue();
});
