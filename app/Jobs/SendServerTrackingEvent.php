<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\TrackingLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendServerTrackingEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [10, 30, 60];

    public function __construct(
        public string $eventName,
        public ?string $eventId,
        public array $userData = [],
        public array $customData = [],
        public ?int $orderId = null,
        public ?string $ipAddress = null,
        public ?string $userAgent = null
    ) {}

    public function handle(): void
    {
        $this->sendToMetaCapi();
        $this->sendToStape();
        $this->sendToGa4();
    }

    /**
     * Send event to Meta Conversions API (CAPI).
     */
    protected function sendToMetaCapi(): void
    {
        $enabled = (bool) setting('meta_pixel_enabled', false);
        $pixelId = (string) setting('meta_pixel_id', '');
        $token = (string) setting('meta_capi_token', '');

        if (!$enabled || empty($pixelId) || empty($token)) {
            return;
        }

        // Prepare SHA-256 hashed user data according to Meta specifications
        $hashedUserData = [];

        if (!empty($this->userData['phone'])) {
            $normalizedPhone = llk_normalize_phone($this->userData['phone']);
            // Prepend BD country code 88 if not already present
            if (!str_starts_with($normalizedPhone, '88')) {
                $normalizedPhone = '88' . $normalizedPhone;
            }
            $hashedUserData['ph'] = [hash('sha256', $normalizedPhone)];
        }

        if (!empty($this->userData['name'])) {
            $hashedUserData['fn'] = [hash('sha256', strtolower(trim($this->userData['name'])))];
        }

        if (!empty($this->userData['city'])) {
            $hashedUserData['ct'] = [hash('sha256', strtolower(trim($this->userData['city'])))];
        }

        $hashedUserData['country'] = [hash('sha256', 'bd')];

        if (!empty($this->ipAddress)) {
            $hashedUserData['client_ip_address'] = $this->ipAddress;
        }

        if (!empty($this->userAgent)) {
            $hashedUserData['client_user_agent'] = $this->userAgent;
        }

        if (!empty($this->userData['fbp'])) {
            $hashedUserData['fbp'] = $this->userData['fbp'];
        }

        if (!empty($this->userData['fbc'])) {
            $hashedUserData['fbc'] = $this->userData['fbc'];
        }

        $payload = [
            'data' => [
                [
                    'event_name' => $this->eventName,
                    'event_time' => time(),
                    'event_id' => $this->eventId,
                    'event_source_url' => $this->customData['source_url'] ?? url()->current(),
                    'action_source' => 'website',
                    'user_data' => $hashedUserData,
                    'custom_data' => [
                        'currency' => 'BDT',
                        'value' => (float) ($this->customData['value'] ?? 0),
                        'order_id' => (string) ($this->customData['order_id'] ?? $this->orderId),
                        'content_name' => $this->customData['content_name'] ?? 'Product',
                        'content_type' => 'product',
                        'contents' => $this->customData['contents'] ?? [],
                    ],
                ]
            ],
        ];

        $testCode = setting('meta_test_event_code');
        if (!empty($testCode)) {
            $payload['test_event_code'] = $testCode;
        }

        try {
            $endpoint = "https://graph.facebook.com/v19.0/{$pixelId}/events?access_token={$token}";
            $response = Http::timeout(10)->post($endpoint, $payload);

            TrackingLog::create([
                'event_name' => $this->eventName,
                'event_id' => $this->eventId,
                'platform' => 'meta_capi',
                'payload' => $payload,
                'response' => $response->body(),
                'status' => $response->successful() ? 'sent' : 'failed',
                'order_id' => $this->orderId,
                'ip_address' => $this->ipAddress,
                'user_agent' => $this->userAgent,
            ]);
        } catch (\Throwable $e) {
            Log::error('Meta CAPI Tracking Error: ' . $e->getMessage());

            TrackingLog::create([
                'event_name' => $this->eventName,
                'event_id' => $this->eventId,
                'platform' => 'meta_capi',
                'payload' => $payload,
                'response' => $e->getMessage(),
                'status' => 'failed',
                'order_id' => $this->orderId,
                'ip_address' => $this->ipAddress,
                'user_agent' => $this->userAgent,
            ]);
        }
    }

    /**
     * Send event to Stape custom gateway / server GTM container.
     */
    protected function sendToStape(): void
    {
        $enabled = (bool) setting('stape_enabled', false);
        $containerUrl = (string) setting('stape_container_url', '');

        if (!$enabled || empty($containerUrl)) {
            return;
        }

        $payload = [
            'client_id' => $this->userData['fbp'] ?? ('llk.' . time()),
            'events' => [
                [
                    'name' => strtolower($this->eventName),
                    'params' => array_merge($this->customData, [
                        'event_id' => $this->eventId,
                        'currency' => 'BDT',
                        'transaction_id' => (string) ($this->customData['order_id'] ?? $this->orderId),
                    ]),
                ]
            ],
            'user_data' => $this->userData,
        ];

        try {
            $url = rtrim($containerUrl, '/') . '/mp/collect';
            $response = Http::timeout(8)->post($url, $payload);

            TrackingLog::create([
                'event_name' => $this->eventName,
                'event_id' => $this->eventId,
                'platform' => 'stape',
                'payload' => $payload,
                'response' => $response->body(),
                'status' => $response->successful() ? 'sent' : 'failed',
                'order_id' => $this->orderId,
                'ip_address' => $this->ipAddress,
                'user_agent' => $this->userAgent,
            ]);
        } catch (\Throwable $e) {
            TrackingLog::create([
                'event_name' => $this->eventName,
                'event_id' => $this->eventId,
                'platform' => 'stape',
                'payload' => $payload,
                'response' => $e->getMessage(),
                'status' => 'failed',
                'order_id' => $this->orderId,
                'ip_address' => $this->ipAddress,
                'user_agent' => $this->userAgent,
            ]);
        }
    }

    /**
     * Send event to Google Analytics 4 Measurement Protocol.
     */
    protected function sendToGa4(): void
    {
        $enabled = (bool) setting('ga4_enabled', false);
        $measurementId = (string) setting('ga4_measurement_id', '');
        $apiSecret = (string) setting('ga4_api_secret', '');

        if (!$enabled || empty($measurementId) || empty($apiSecret)) {
            return;
        }

        $gaEventName = match ($this->eventName) {
            'Purchase' => 'purchase',
            'Lead' => 'generate_lead',
            'PageView' => 'page_view',
            'AddToCart' => 'add_to_cart',
            'InitiateCheckout' => 'begin_checkout',
            default => strtolower($this->eventName),
        };

        $payload = [
            'client_id' => $this->userData['ga_client_id'] ?? ('GA1.1.' . mt_rand(100000000, 999999999) . '.' . time()),
            'events' => [
                [
                    'name' => $gaEventName,
                    'params' => [
                        'currency' => 'BDT',
                        'value' => (float) ($this->customData['value'] ?? 0),
                        'transaction_id' => (string) ($this->customData['order_id'] ?? $this->orderId),
                    ],
                ]
            ],
        ];

        try {
            $endpoint = "https://www.google-analytics.com/mp/collect?measurement_id={$measurementId}&api_secret={$apiSecret}";
            $response = Http::timeout(8)->post($endpoint, $payload);

            TrackingLog::create([
                'event_name' => $this->eventName,
                'event_id' => $this->eventId,
                'platform' => 'ga4',
                'payload' => $payload,
                'response' => $response->body() ?: 'Accepted',
                'status' => $response->successful() ? 'sent' : 'failed',
                'order_id' => $this->orderId,
                'ip_address' => $this->ipAddress,
                'user_agent' => $this->userAgent,
            ]);
        } catch (\Throwable $e) {
            // Handled
        }
    }
}
