<?php

declare(strict_types=1);

use App\Models\Blocklist;
use App\Models\Customer;
use App\Models\DeliveryZone;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOffer;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use ImranDev\UniversalSlug\Models\SlugHistory;

beforeEach(function () {
    $this->withoutMiddleware([
        ThrottleRequests::class,
        ThrottleRequestsWithRedis::class,
    ]);
});

test('root product slug renders product landing page', function () {
    $product = Product::create([
        'name' => 'Organic Pure Mustard Oil',
        'slug' => 'organic-pure-mustard-oil',
        'regular_price' => 500.00,
        'sale_price' => 450.00,
        'status' => 'active',
    ]);

    $response = $this->get('/organic-pure-mustard-oil');
    $response->assertStatus(200);
    $response->assertSee('Organic Pure Mustard Oil');
    $response->assertSee('450');
    $response->assertSee('অর্ডার করতে নিচের ফর্মটি পূরণ করুন');
});

test('reserved slugs are not intercepted by product route', function () {
    // Attempting to visit /track-order or /admin should not treat them as products
    $response = $this->get('/track-order');
    $response->assertStatus(200);
    $response->assertSee('আপনার অর্ডার ট্র্যাক করুন');
});

test('historical slug returns 301 redirect to updated product slug', function () {
    $product = Product::create([
        'name' => 'Smart Watch Pro',
        'slug' => 'smart-watch-pro-updated',
        'regular_price' => 3000.00,
        'status' => 'active',
    ]);

    // Record historical slug in slug_histories table
    SlugHistory::create([
        'sluggable_type' => Product::class,
        'sluggable_id' => $product->id,
        'slug' => 'smart-watch-pro-old',
        'field' => 'slug',
    ]);

    $response = $this->get('/smart-watch-pro-old');
    $response->assertStatus(301);
    $response->assertRedirect('/smart-watch-pro-updated');
});

test('customer lookup returns masked info for returning customer and not found for new customer', function () {
    $district = District::first();
    Customer::create([
        'phone' => '01711223344',
        'name' => 'Tariqul Islam',
        'default_address' => 'House 5, Road 2, Banani',
        'district_id' => $district->id,
    ]);

    // Existing customer lookup
    $response = $this->getJson('/api/checkout/customer-lookup?phone=01711223344');
    $response->assertStatus(200);
    $response->assertJson([
        'valid' => true,
        'found' => true,
        'name' => 'Tariqul Islam',
        'default_address' => 'House 5, Road 2, Banani',
    ]);

    // Lookup with Bangladeshi +880 prefix and spaces
    $prefixResponse = $this->getJson('/api/checkout/customer-lookup?phone=+8801711223344');
    $prefixResponse->assertStatus(200);
    $prefixResponse->assertJson(['found' => true]);

    // Non-existent customer lookup
    $newResponse = $this->getJson('/api/checkout/customer-lookup?phone=01899887766');
    $newResponse->assertStatus(200);
    $newResponse->assertJson(['valid' => true, 'found' => false]);

    // Blocked customer lookup
    Blocklist::create([
        'type' => 'phone',
        'value' => '01711223344',
        'reason' => 'Fraudulent orders',
    ]);

    $blockedResponse = $this->getJson('/api/checkout/customer-lookup?phone=01711223344');
    $blockedResponse->assertStatus(200);
    $blockedResponse->assertJson(['blocked' => true]);
});

test('capture lead records incomplete order in database', function () {
    $product = Product::create([
        'name' => 'Leather Wallet',
        'slug' => 'leather-wallet-lead',
        'regular_price' => 1200.00,
        'status' => 'active',
    ]);

    $response = $this->postJson('/api/checkout/capture-lead', [
        'phone' => '01655443322',
        'product_id' => $product->id,
        'quantity' => 2,
        'name' => 'Partial Customer',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('incomplete_orders', [
        'phone' => '01655443322',
        'product_id' => $product->id,
        'status' => 'lead',
        'quantity' => 2,
    ]);
});

test('order submission creates customer and order, calculates volume discount and redirects to success', function () {
    $district = District::first();
    $zone = DeliveryZone::first();

    $product = Product::create([
        'name' => 'Royal Saffron Attar',
        'slug' => 'royal-saffron-attar',
        'regular_price' => 1000.00,
        'sale_price' => 800.00,
        'status' => 'active',
    ]);

    // Add Buy 2 get 100 off offer
    ProductOffer::create([
        'product_id' => $product->id,
        'type' => 'bundle',
        'name' => 'Buy 2 Get 100 Off',
        'title' => 'Buy 2 Get 100 Off',
        'discount_type' => 'fixed',
        'discount_amount' => 100.00,
        'min_quantity' => 2,
        'is_active' => true,
    ]);

    // Submit order for 2 items
    $response = $this->post('/checkout/order', [
        'phone' => '01799887766',
        'name' => 'Mahmudul Hasan',
        'address' => 'Flat 4B, Green Road',
        'district_id' => $district->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'delivery_zone_id' => $zone->id,
        'payment_method' => 'cod',
    ]);

    $response->assertStatus(302);

    $order = Order::where('customer_phone', '01799887766')->first();
    expect($order)->not->toBeNull();
    expect($order->subtotal)->toBe('1600.00'); // 800 * 2
    expect($order->discount_amount)->toBe('100.00'); // volume discount
    expect((float) $order->total_amount)->toBe(1600.00 - 100.00 + (float) $zone->charge);

    $response->assertRedirect(route('order.success', ['token' => $order->order_token]));

    // Customer record created
    $this->assertDatabaseHas('customers', [
        'phone' => '01799887766',
        'name' => 'Mahmudul Hasan',
    ]);
});

test('success page sets purchase tracked timestamp and prevents duplicate purchase event', function () {
    $district = District::first();
    $customer = Customer::create(['phone' => '01700112233', 'name' => 'Buyer', 'district_id' => $district->id]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01700112233',
        'customer_name' => 'Buyer',
        'customer_address' => 'Dhaka',
        'district_id' => $district->id,
        'subtotal' => 1000.00,
        'delivery_charge' => 60.00,
        'total_amount' => 1060.00,
        'purchase_tracked_at' => null,
    ]);

    // First visit to order-success: should fire purchase event
    $firstResponse = $this->get('/order-success/'.$order->order_token);
    $firstResponse->assertStatus(200);
    $firstResponse->assertSee('window.dataLayer.push');
    $firstResponse->assertSee("event: 'purchase'", false);

    // Verify order now has purchase_tracked_at set
    $order->refresh();
    expect($order->purchase_tracked_at)->not->toBeNull();

    // Second visit (page refresh): should NOT fire purchase event
    $secondResponse = $this->get('/order-success/'.$order->order_token);
    $secondResponse->assertStatus(200);
    $secondResponse->assertDontSee("event: 'purchase'", false);
});

test('duplicate order within configured window is detected', function () {
    $district = District::first();
    $product = Product::create([
        'name' => 'Winter Jacket',
        'slug' => 'winter-jacket-phase4',
        'regular_price' => 2000.00,
        'status' => 'active',
    ]);

    // Submit first order
    $firstOrderResponse = $this->post('/checkout/order', [
        'phone' => '01855667788',
        'name' => 'Shahidul Islam',
        'address' => 'Chittagong',
        'district_id' => $district->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $firstOrderResponse->assertSessionHasNoErrors();
    expect(Order::where('customer_phone', '01855667788')->count())->toBe(1);

    // Immediately submit duplicate order with same phone and product
    $duplicateResponse = $this->post('/checkout/order', [
        'phone' => '01855667788',
        'name' => 'Shahidul Islam',
        'address' => 'Chittagong',
        'district_id' => $district->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    // Should redirect to existing order without creating a duplicate order
    expect(Order::where('customer_phone', '01855667788')->count())->toBe(1);
});

test('order tracking page finds order and renders status timeline', function () {
    $district = District::first();
    $customer = Customer::create(['phone' => '01911223355', 'name' => 'Track Tester', 'district_id' => $district->id]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'customer_phone' => '01911223355',
        'customer_name' => 'Track Tester',
        'customer_address' => 'Sylhet',
        'district_id' => $district->id,
        'subtotal' => 500.00,
        'delivery_charge' => 120.00,
        'total_amount' => 620.00,
    ]);

    $response = $this->get('/track-order?order_number='.$order->order_number.'&phone=01911223355');
    $response->assertStatus(200);
    $response->assertSee($order->order_number);
    $response->assertSee('অর্ডার প্রগ্রেস টাইমলাইন');
});
