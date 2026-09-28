<?php

declare(strict_types=1);

namespace App\Services\Courier;

use App\Contracts\CourierDriverInterface;
use App\DTOs\CourierShipmentResult;
use App\DTOs\CourierTrackingResult;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class RedXCourierDriver implements CourierDriverInterface
{
    protected string $baseUrl = 'https://openapi.redx.com.bd/v1.0.0-beta';

    public function sendOrder(Order $order): CourierShipmentResult
    {
        $token = (string) setting('redx_api_token');
        if (empty($token)) {
            return new CourierShipmentResult(success: false, message: 'RedX API token is missing in Settings.');
        }

        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/parcels", [
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'delivery_area' => $order->district?->name_en ?? 'Dhaka',
                'customer_address' => $order->customer_address,
                'merchant_invoice_id' => $order->order_number,
                'cash_collection_amount' => (int) round((float) $order->total_amount),
                'parcel_weight' => 500,
                'instruction' => $order->customer_notes ?? '',
            ]);

            $data = $response->json() ?? [];

            if ($response->successful() && !empty($data['tracking_id'])) {
                $trackingId = (string) $data['tracking_id'];
                return new CourierShipmentResult(
                    success: true,
                    consignmentId: $trackingId,
                    trackingCode: $trackingId,
                    trackingUrl: "https://redx.com.bd/track/{$trackingId}",
                    status: 'Created',
                    message: 'Parcel placed with RedX Courier.',
                    rawResponse: $data
                );
            }

            return new CourierShipmentResult(
                success: false,
                message: $data['message'] ?? 'Failed to create RedX parcel.',
                rawResponse: $data
            );
        } catch (\Throwable $e) {
            return new CourierShipmentResult(success: false, message: 'RedX Exception: ' . $e->getMessage());
        }
    }

    public function trackOrder(Order $order): CourierTrackingResult
    {
        $token = (string) setting('redx_api_token');
        if (empty($token) || !$order->courier_tracking_id) {
            return new CourierTrackingResult(success: false, status: 'unknown', message: 'No tracking ID or token');
        }

        try {
            $response = Http::withToken($token)->get("{$this->baseUrl}/parcels/{$order->courier_tracking_id}");
            $data = $response->json() ?? [];
            $status = $data['parcel']['status'] ?? 'unknown';

            return new CourierTrackingResult(
                success: $response->successful(),
                status: $status,
                latestUpdate: "Status: {$status}",
                history: $data
            );
        } catch (\Throwable $e) {
            return new CourierTrackingResult(success: false, status: 'error', message: $e->getMessage());
        }
    }

    public function cancelShipment(Order $order): bool
    {
        return false;
    }
}
