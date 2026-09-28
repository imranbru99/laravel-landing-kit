<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use App\Jobs\SendServerTrackingEvent;
use App\Models\IncompleteOrder;
use App\Models\Order;
use Illuminate\Support\Str;

class TrackingManager
{
    /**
     * Dispatch Purchase tracking event (CAPI & Server side).
     */
    public function trackPurchase(Order $order, ?string $eventId = null): void
    {
        $eventId = $eventId ?: 'order_' . $order->id . '_' . Str::random(8);

        $customer = $order->customer;
        $userData = [
            'phone' => $customer?->phone,
            'name' => $customer?->name,
            'city' => $order->district?->name_en ?? 'Dhaka',
            'fbp' => request()->cookie('_fbp'),
            'fbc' => request()->cookie('_fbc'),
        ];

        $items = $order->items->map(fn ($item) => [
            'id' => (string) $item->product_id,
            'name' => $item->product_name,
            'quantity' => $item->quantity,
            'item_price' => (float) $item->unit_price,
        ])->toArray();

        $customData = [
            'order_id' => $order->order_number,
            'value' => (float) $order->total_amount,
            'currency' => 'BDT',
            'contents' => $items,
            'source_url' => url()->current(),
        ];

        SendServerTrackingEvent::dispatch(
            'Purchase',
            $eventId,
            $userData,
            $customData,
            $order->id,
            request()->ip(),
            request()->userAgent()
        );
    }

    /**
     * Dispatch Lead tracking event.
     */
    public function trackLead(IncompleteOrder $lead, ?string $eventId = null): void
    {
        $eventId = $eventId ?: 'lead_' . $lead->id . '_' . Str::random(8);

        $userData = [
            'phone' => $lead->phone,
            'name' => $lead->name,
            'fbp' => request()->cookie('_fbp'),
            'fbc' => request()->cookie('_fbc'),
        ];

        $customData = [
            'lead_id' => $lead->id,
            'source_url' => url()->current(),
        ];

        SendServerTrackingEvent::dispatch(
            'Lead',
            $eventId,
            $userData,
            $customData,
            null,
            request()->ip(),
            request()->userAgent()
        );
    }

    /**
     * Send a test event to verify Meta CAPI or Stape connection.
     */
    public function sendTestEvent(string $platform = 'meta_capi'): void
    {
        $eventId = 'test_' . time();
        $userData = [
            'phone' => '01700000000',
            'name' => 'Test User',
            'city' => 'Dhaka',
        ];

        $customData = [
            'order_id' => 'TEST-001',
            'value' => 1000.0,
            'currency' => 'BDT',
            'source_url' => url('/'),
        ];

        SendServerTrackingEvent::dispatchSync(
            'PageView',
            $eventId,
            $userData,
            $customData,
            null,
            request()->ip() ?: '127.0.0.1',
            request()->userAgent() ?: 'LLK-Testing'
        );
    }
}
