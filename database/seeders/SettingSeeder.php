<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\SettingService;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingService = app(SettingService::class);

        $defaults = [
            // General
            ['key' => 'site_name', 'value' => 'Amar Shop BD', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_tagline', 'value' => 'Premium E-Commerce Landing Pages in Bangladesh', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency', 'value' => 'BDT', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => '৳', 'group' => 'general', 'type' => 'string'],
            ['key' => 'use_bangla_numerals', 'value' => true, 'group' => 'general', 'type' => 'boolean'],
            ['key' => 'primary_phone', 'value' => '01700000000', 'group' => 'general', 'type' => 'string'],
            ['key' => 'whatsapp_number', 'value' => '01700000000', 'group' => 'general', 'type' => 'string'],
            ['key' => 'support_email', 'value' => 'support@amarshopbd.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'default_language', 'value' => 'bn', 'group' => 'general', 'type' => 'string'],

            // Checkout
            ['key' => 'enable_cod', 'value' => true, 'group' => 'checkout', 'type' => 'boolean'],
            ['key' => 'enable_bkash_manual', 'value' => false, 'group' => 'checkout', 'type' => 'boolean'],
            ['key' => 'bkash_manual_number', 'value' => '01700000000 (Personal)', 'group' => 'checkout', 'type' => 'string'],
            ['key' => 'enable_nagad_manual', 'value' => false, 'group' => 'checkout', 'type' => 'boolean'],
            ['key' => 'nagad_manual_number', 'value' => '01800000000 (Personal)', 'group' => 'checkout', 'type' => 'string'],
            ['key' => 'default_inside_dhaka_charge', 'value' => 60, 'group' => 'checkout', 'type' => 'integer'],
            ['key' => 'default_outside_dhaka_charge', 'value' => 120, 'group' => 'checkout', 'type' => 'integer'],
            ['key' => 'free_delivery_threshold', 'value' => 0, 'group' => 'checkout', 'type' => 'integer'],
            ['key' => 'duplicate_order_window_minutes', 'value' => 10, 'group' => 'checkout', 'type' => 'integer'],
            ['key' => 'mask_guest_lookup', 'value' => true, 'group' => 'checkout', 'type' => 'boolean'],
            ['key' => 'order_notes_enabled', 'value' => true, 'group' => 'checkout', 'type' => 'boolean'],
            ['key' => 'success_page_message', 'value' => 'আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে! শীঘ্রই আমাদের প্রতিনিধি আপনার সাথে যোগাযোগ করবেন।', 'group' => 'checkout', 'type' => 'string'],

            // Order Settings
            ['key' => 'order_number_prefix', 'value' => 'ORD', 'group' => 'order', 'type' => 'string'],
            ['key' => 'stock_reduction_rule', 'value' => 'on_confirm', 'group' => 'order', 'type' => 'string'], // on_order or on_confirm
            ['key' => 'default_order_status', 'value' => 'pending', 'group' => 'order', 'type' => 'string'],

            // Tracking Defaults
            ['key' => 'gtm_enabled', 'value' => false, 'group' => 'tracking', 'type' => 'boolean'],
            ['key' => 'gtm_container_id', 'value' => '', 'group' => 'tracking', 'type' => 'string'],
            ['key' => 'gtm_custom_domain', 'value' => '', 'group' => 'tracking', 'type' => 'string'],
            ['key' => 'meta_pixel_enabled', 'value' => false, 'group' => 'tracking', 'type' => 'boolean'],
            ['key' => 'meta_pixel_id', 'value' => '', 'group' => 'tracking', 'type' => 'string'],
            ['key' => 'meta_capi_enabled', 'value' => false, 'group' => 'tracking', 'type' => 'boolean'],
            ['key' => 'meta_capi_token', 'value' => '', 'group' => 'tracking', 'type' => 'encrypted', 'is_encrypted' => true],
            ['key' => 'meta_test_code', 'value' => '', 'group' => 'tracking', 'type' => 'string'],
            ['key' => 'stape_enabled', 'value' => false, 'group' => 'tracking', 'type' => 'boolean'],
            ['key' => 'stape_domain', 'value' => '', 'group' => 'tracking', 'type' => 'string'],
            ['key' => 'ga4_enabled', 'value' => false, 'group' => 'tracking', 'type' => 'boolean'],
            ['key' => 'ga4_measurement_id', 'value' => '', 'group' => 'tracking', 'type' => 'string'],
            ['key' => 'tiktok_pixel_enabled', 'value' => false, 'group' => 'tracking', 'type' => 'boolean'],
            ['key' => 'tiktok_pixel_id', 'value' => '', 'group' => 'tracking', 'type' => 'string'],

            // AI Settings
            ['key' => 'ai_provider', 'value' => 'gemini', 'group' => 'ai', 'type' => 'string'],
            ['key' => 'ai_model', 'value' => 'gemini-2.5-flash', 'group' => 'ai', 'type' => 'string'],
            ['key' => 'ai_gemini_key', 'value' => '', 'group' => 'ai', 'type' => 'encrypted', 'is_encrypted' => true],
            ['key' => 'ai_openai_key', 'value' => '', 'group' => 'ai', 'type' => 'encrypted', 'is_encrypted' => true],

            // Courier Defaults
            ['key' => 'default_courier', 'value' => 'manual', 'group' => 'courier', 'type' => 'string'],
            ['key' => 'steadfast_api_key', 'value' => '', 'group' => 'courier', 'type' => 'encrypted', 'is_encrypted' => true],
            ['key' => 'steadfast_secret_key', 'value' => '', 'group' => 'courier', 'type' => 'encrypted', 'is_encrypted' => true],
            ['key' => 'pathao_client_id', 'value' => '', 'group' => 'courier', 'type' => 'string'],
            ['key' => 'pathao_client_secret', 'value' => '', 'group' => 'courier', 'type' => 'encrypted', 'is_encrypted' => true],
            ['key' => 'pathao_username', 'value' => '', 'group' => 'courier', 'type' => 'string'],
            ['key' => 'pathao_password', 'value' => '', 'group' => 'courier', 'type' => 'encrypted', 'is_encrypted' => true],
            ['key' => 'redx_api_token', 'value' => '', 'group' => 'courier', 'type' => 'encrypted', 'is_encrypted' => true],
        ];

        foreach ($defaults as $item) {
            $settingService->set(
                key: $item['key'],
                value: $item['value'],
                group: $item['group'],
                type: $item['type'] ?? 'string',
                isEncrypted: $item['is_encrypted'] ?? false
            );
        }
    }
}
