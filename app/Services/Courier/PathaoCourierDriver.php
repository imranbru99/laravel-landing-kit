<?php

declare(strict_types=1);

namespace App\Services\Courier;

use App\Contracts\CourierDriverInterface;
use App\DTOs\CourierShipmentResult;
use App\DTOs\CourierTrackingResult;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PathaoCourierDriver implements CourierDriverInterface
{
    protected string $baseUrl = 'https://api-hermes.pathao.com';

    public function sendOrder(Order $order): CourierShipmentResult
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return new CourierShipmentResult(
                success: false,
                message: 'Failed to authenticate with Pathao API. Check credentials in Settings.'
            );
        }

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/aladdin/api/v1/orders", [
                    'merchant_order_id' => $order->order_number,
                    'recipient_name' => $order->customer_name,
                    'recipient_phone' => $order->customer_phone,
                    'recipient_address' => $order->customer_address,
                    'recipient_city' => $order->district_id ?? 1,
                    'recipient_zone' => $order->thana_id ?? 1,
                    'amount_to_collect' => (int) round((float) $order->total_amount),
                    'item_type' => 1, // parcel
                    'item_quantity' => $order->items->sum('quantity') ?: 1,
                    'item_weight' => 0.5,
                    'delivery_type' => 48,
                    'item_description' => $order->items->pluck('product_name')->implode(', '),
                ]);

            $data = $response->json() ?? [];

            if ($response->successful() && !empty($data['data']['consignment_id'])) {
                $consignmentId = (string) $data['data']['consignment_id'];
                return new CourierShipmentResult(
                    success: true,
                    consignmentId: $consignmentId,
                    trackingCode: $consignmentId,
                    trackingUrl: "https://merchant.pathao.com/tracking?consignment_id={$consignmentId}",
                    status: 'Order Created',
                    message: 'Order dispatched to Pathao Courier.',
                    rawResponse: $data
                );
            }

            return new CourierShipmentResult(
                success: false,
                message: $data['message'] ?? 'Pathao order creation failed.',
                rawResponse: $data
            );
        } catch (\Throwable $e) {
            return new CourierShipmentResult(success: false, message: 'Pathao exception: ' . $e->getMessage());
        }
    }

    public function trackOrder(Order $order): CourierTrackingResult
    {
        $token = $this->getAccessToken();
        if (!$token || !$order->courier_consignment_id) {
            return new CourierTrackingResult(success: false, status: 'unknown', message: 'No Pathao token or consignment ID');
        }

        try {
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/aladdin/api/v1/orders/{$order->courier_consignment_id}/info");

            $data = $response->json() ?? [];
            $status = $data['data']['order_status'] ?? 'unknown';

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

    protected function getAccessToken(): ?string
    {
        return Cache::remember('pathao_courier_access_token', 86000, function () {
            $clientId = (string) setting('pathao_client_id');
            $clientSecret = (string) setting('pathao_client_secret');
            $username = (string) setting('pathao_username');
            $password = (string) setting('pathao_password');

            if (empty($clientId) || empty($clientSecret) || empty($username) || empty($password)) {
                return null;
            }

            try {
                $response = Http::post("{$this->baseUrl}/aladdin/api/v1/issue-token", [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'username' => $username,
                    'password' => $password,
                    'grant_type' => 'password',
                ]);

                return $response->json('access_token');
            } catch (\Throwable) {
                return null;
            }
        });
    }
}
