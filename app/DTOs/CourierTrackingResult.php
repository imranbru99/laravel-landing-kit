<?php

declare(strict_types=1);

namespace App\DTOs;

class CourierTrackingResult
{
    public function __construct(
        public bool $success,
        public string $status,
        public ?string $latestUpdate = null,
        public array $history = [],
        public ?string $message = null
    ) {}

    public function isDelivered(): bool
    {
        return in_array(strtolower($this->status), ['delivered', 'successful'], true);
    }
}
