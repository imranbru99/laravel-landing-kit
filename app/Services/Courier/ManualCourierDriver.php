<?php

declare(strict_types=1);

namespace App\Services\Courier;

use App\Contracts\CourierDriverInterface;
use App\DTOs\CourierShipmentResult;
use App\DTOs\CourierTrackingResult;
use App\Models\Order;
use Illuminate\Support\Str;

class ManualCourierDriver implements CourierDriverInterface
{
    public function sendOrder(Order $order): CourierShipmentResult
    {
        $tracking = 'MANUAL-' . strtoupper(Str::random(8));

        return new CourierShipmentResult(
            success: true,
            consignmentId: $tracking,
            trackingCode: $tracking,
            trackingUrl: null,
            status: 'in_review',
            message: 'Order recorded for manual in-house delivery.',
            rawResponse: ['driver' => 'manual', 'time' => now()->toIso8601String()]
        );
    }

    public function trackOrder(Order $order): CourierTrackingResult
    {
        return new CourierTrackingResult(
            success: true,
            status: $order->status->value,
            latestUpdate: 'Handled in-house.',
            history: []
        );
    }

    public function cancelShipment(Order $order): bool
    {
        return true;
    }
}
