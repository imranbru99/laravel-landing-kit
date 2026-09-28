<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SettingService
{
    private const CACHE_KEY = 'llk_settings_all';

    /**
     * Get a setting by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, mixed $value, string $group = 'general', ?string $type = null, bool $isEncrypted = false): void
    {
        if ($type === null) {
            $type = match (true) {
                is_bool($value) => 'boolean',
                is_int($value) => 'integer',
                is_float($value) => 'float',
                is_array($value) => 'json',
                default => 'string',
            };
        }

        $rawValue = match ($type) {
            'json', 'array' => json_encode($value, JSON_UNESCAPED_UNICODE),
            'boolean', 'bool' => $value ? '1' : '0',
            default => (string) $value,
        };

        if ($isEncrypted && ! empty($rawValue)) {
            $rawValue = Crypt::encryptString($rawValue);
        }

        Setting::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $rawValue,
                'type' => $type,
                'is_encrypted' => $isEncrypted,
            ]
        );

        $this->clearCache();
    }

    /**
     * Get all settings as key => parsed value array.
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            try {
                $rows = Setting::all();
                $output = [];
                foreach ($rows as $row) {
                    $output[$row->key] = $row->parsed_value;
                }

                return $output;
            } catch (\Throwable) {
                return [];
            }
        });
    }

    /**
     * Get all settings grouped by group name.
     */
    public function allByGroup(string $group): array
    {
        $all = $this->all();
        $groupSettings = [];

        try {
            $keys = Setting::where('group', $group)->pluck('key')->all();
            foreach ($keys as $k) {
                if (array_key_exists($k, $all)) {
                    $groupSettings[$k] = $all[$k];
                }
            }
        } catch (\Throwable) {
            // Ignore if DB not ready
        }

        return $groupSettings;
    }

    /**
     * Invalidate settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
