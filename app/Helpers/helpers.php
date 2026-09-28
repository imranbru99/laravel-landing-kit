<?php

declare(strict_types=1);

use App\Services\SettingService;

if (!function_exists('setting')) {
    /**
     * Get a setting or the SettingService instance.
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        $service = app(SettingService::class);

        if ($key === null) {
            return $service;
        }

        return $service->get($key, $default);
    }
}

if (!function_exists('llk_bn_number')) {
    /**
     * Convert English digits to Bengali numerals.
     */
    function llk_bn_number(int|float|string $number): string
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace($en, $bn, (string) $number);
    }
}

if (!function_exists('llk_en_number')) {
    /**
     * Convert Bengali numerals to English digits.
     */
    function llk_en_number(string $number): string
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace($bn, $en, $number);
    }
}

if (!function_exists('llk_normalize_phone')) {
    /**
     * Normalize Bangladeshi phone number to 01XXXXXXXXX format.
     */
    function llk_normalize_phone(string $phone): string
    {
        // Convert any Bengali numerals first
        $cleaned = llk_en_number($phone);
        // Strip non-digits
        $cleaned = preg_replace('/[^\d]/', '', $cleaned);

        // Remove leading 880 or +880
        if (str_starts_with($cleaned, '880')) {
            $cleaned = substr($cleaned, 3);
        }

        // If it starts with 1 and is 10 digits, add 0
        if (strlen($cleaned) === 10 && str_starts_with($cleaned, '1')) {
            $cleaned = '0' . $cleaned;
        }

        return $cleaned;
    }
}

if (!function_exists('llk_is_valid_bd_phone')) {
    /**
     * Validate if normalized phone is a valid Bangladeshi number.
     */
    function llk_is_valid_bd_phone(string $phone): bool
    {
        $normalized = llk_normalize_phone($phone);
        return (bool) preg_match('/^01[3-9]\d{8}$/', $normalized);
    }
}

if (!function_exists('llk_currency')) {
    /**
     * Format currency in BDT with optional Bangla numerals.
     */
    function llk_currency(float|int|string|null $amount, bool $forceEnglish = false): string
    {
        $amount = (float) ($amount ?? 0);
        $formatted = number_format($amount, 0, '.', ',');

        $useBangla = !$forceEnglish && (bool) setting('use_bangla_numerals', false);
        if ($useBangla) {
            $formatted = llk_bn_number($formatted);
            return '৳ ' . $formatted;
        }

        $symbol = (string) setting('currency_symbol', '৳');
        return $symbol . ' ' . $formatted;
    }
}
