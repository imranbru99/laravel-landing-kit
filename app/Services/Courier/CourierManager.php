<?php

declare(strict_types=1);

namespace App\Services\Courier;

use App\Contracts\CourierDriverInterface;
use InvalidArgumentException;

class CourierManager
{
    /**
     * Resolve courier driver by name.
     */
    public function driver(?string $driver = null): CourierDriverInterface
    {
        $driver = $driver ?? (string) setting('default_courier', 'manual');

        return match (strtolower($driver)) {
            'steadfast' => app(SteadfastCourierDriver::class),
            'pathao' => app(PathaoCourierDriver::class),
            'redx' => app(RedXCourierDriver::class),
            'manual' => app(ManualCourierDriver::class),
            default => throw new InvalidArgumentException("Unsupported courier driver [{$driver}]."),
        };
    }
}
