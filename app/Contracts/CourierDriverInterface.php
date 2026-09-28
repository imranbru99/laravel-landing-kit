<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\CourierShipmentResult;
use App\DTOs\CourierTrackingResult;
use App\Models\Order;

interface CourierDriverInterface
{
    /**
     * Dispatch single order to courier.
     */
    public function sendOrder(Order $order): CourierShipmentResult;

    /**
     * Track shipment status.
     */
    public function trackOrder(Order $order): CourierTrackingResult;

    /**
     * Cancel consignment.
     */
    public function cancelShipment(Order $order): bool;
}
