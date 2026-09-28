<?php

declare(strict_types=1);

namespace App\Services\Courier;

use App\Contracts\CourierDriverInterface;
use App\DTOs\CourierShipmentResult;
use App\DTOs\CourierTrackingResult;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class SteadfastCourierDriver implements CourierDriverInterface
{
    protected string $baseUrl = 'https://portal.steadfast.com.bd/api/v1';

    public function sendOrder(Order $order): CourierShipmentResult
    {
        $apiKey = (string) setting('steadfast_api_key');
        $secretKey = (string) setting('steadfast_secret_key');

        if (empty($apiKey) || empty($secretKey)) {
            return new CourierShipmentResult(
                success: false,
                message: 'Steadfast API Key or Secret Key is missing in Settings.'
            );
        }

        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
                'Secret-Key' => $secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/create_order", [
                'invoice' => $order->order_number,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'recipient_address' => $order->customer_address,
                'cod_amount' => (int) round((float) $order->total_amount),
                'note' => $order->customer_notes ?? 'Handle with care',
            ]);

            $data = $response->json() ?? [];

            if ($response->successful() && ($data['status'] ?? null) === 200) {
                $consignment = $data['consignment'] ?? [];
                $consignmentId = (string) ($consignment['consignment_id'] ?? '');
                $trackingCode = (string) ($consignment['tracking_code'] ?? $consignmentId);

                return new CourierShipmentResult(
                    success: true,
                    consignmentId: $consignmentId,
                    trackingCode: $trackingCode,
                    trackingUrl: "https://steadfast.com.bd/t/{$trackingCode}",
                    status: 'in_review',
                    message: 'Order placed to Steadfast Courier successfully.',
                    rawResponse: $data
                );
            }

            return new CourierShipmentResult(
                success: false,
                message: $data['message'] ?? 'Steadfast API error: ' . $response->body(),
                rawResponse: $data
            );
        } catch (\Throwable $e) {
            return new CourierShipmentResult(
                success: false,
                message: 'Steadfast HTTP Exception: ' . $e->getMessage()
            );
        }
    }

    public function trackOrder(Order $order): CourierTrackingResult
    {
        $apiKey = (string) setting('steadfast_api_key');
        $secretKey = (string) setting('steadfast_secret_key');

        if (!$order->courier_consignment_id) {
            return new CourierTrackingResult(success: false, status: 'unknown', message: 'No consignment ID');
        }

        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
                'Secret-Key' => $secretKey,
            ])->get("{$this->baseUrl}/status_by_cid/{$order->courier_consignment_id}");

            $data = $response->json() ?? [];
            $status = $data['delivery_status'] ?? 'unknown';

            return new CourierTrackingResult(
                success: $response->successful(),
                status: $status,
                latestUpdate: "Status from Steadfast: {$status}",
                history: $data
            );
        } catch (\Throwable $e) {
            return new CourierTrackingResult(success: false, status: 'error', message: $e->getMessage());
        }
    }

    public function cancelShipment(Order $order): bool
    {
        return false; // Steadfast requires portal cancellation once processed
    }
}
