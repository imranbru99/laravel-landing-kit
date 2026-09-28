<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Jobs\SendServerTrackingEvent;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TrackingLog;
use App\Services\Tracking\TrackingManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class Phase8TrackingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_send_server_tracking_event_executes_and_logs_to_database(): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response(['events_received' => 1], 200),
        ]);

        setting()->set('meta_pixel_enabled', true);
        setting()->set('meta_pixel_id', '123456789');
        setting()->set('meta_capi_token', 'dummy_token');

        $job = new SendServerTrackingEvent(
            eventName: 'Purchase',
            eventId: 'test_event_123',
            userData: [
                'phone' => '01712345678',
                'name' => 'Md Karim',
                'city' => 'Dhaka',
            ],
            customData: [
                'value' => 2500.0,
                'order_id' => 'ORD-12345',
            ],
            orderId: null,
            ipAddress: '127.0.0.1',
            userAgent: 'Mozilla/5.0'
        );

        $job->handle();

        $this->assertDatabaseHas('tracking_logs', [
            'event_name' => 'Purchase',
            'event_id' => 'test_event_123',
            'platform' => 'meta_capi',
        ]);

        $log = TrackingLog::where('event_id', 'test_event_123')->first();
        $this->assertNotNull($log);
        $payload = $log->payload;

        // Verify SHA-256 hashed phone with international prefix 8801712345678
        $expectedHashedPhone = hash('sha256', '8801712345678');
        $this->assertEquals($expectedHashedPhone, $payload['data'][0]['user_data']['ph'][0]);

        // Verify SHA-256 hashed name
        $expectedHashedName = hash('sha256', 'md karim');
        $this->assertEquals($expectedHashedName, $payload['data'][0]['user_data']['fn'][0]);
    }

    public function test_tracking_manager_dispatches_server_event_on_purchase(): void
    {
        Queue::fake();

        $district = District::first() ?? District::create(['name_en' => 'Dhaka', 'name_bn' => 'ঢাকা', 'division' => 'Dhaka']);
        $product = Product::create([
            'name' => 'Tracking Test Product',
            'regular_price' => 1200,
            'status' => 'active',
        ]);

        $customer = Customer::create([
            'phone' => '01811112222',
            'name' => 'Rahim Ullah',
        ]);

        $order = Order::create([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_address' => 'Mirpur 10, Dhaka',
            'district_id' => $district->id,
            'status' => OrderStatus::Pending,
            'subtotal' => 1200,
            'delivery_charge' => 60,
            'total_amount' => 1260,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 1200,
            'quantity' => 1,
            'total_price' => 1200,
        ]);

        /** @var TrackingManager $manager */
        $manager = app(TrackingManager::class);
        $manager->trackPurchase($order, 'purchase_'.$order->id);

        Queue::assertPushed(SendServerTrackingEvent::class, function ($job) use ($order) {
            return $job->eventName === 'Purchase' && $job->eventId === 'purchase_'.$order->id;
        });
    }

    public function test_tracking_head_component_injects_datalayer_and_helpers(): void
    {
        setting()->set('gtm_enabled', true);
        setting()->set('gtm_id', 'GTM-TEST1234');
        setting()->set('meta_pixel_enabled', true);
        setting()->set('meta_pixel_id', '999888777');

        $html = view('components.tracking.head')->render();

        $this->assertStringContainsString('window.dataLayer', $html);
        $this->assertStringContainsString('window.LLK.track', $html);
        $this->assertStringContainsString('GTM-TEST1234', $html);
        $this->assertStringContainsString('999888777', $html);
        $this->assertStringContainsString("gtag('consent', 'default'", $html);
    }
}
