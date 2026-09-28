<?php

declare(strict_types=1);

namespace App\DTOs;

class CourierShipmentResult
{
    public function __construct(
        public bool $success,
        public ?string $consignmentId = null,
        public ?string $trackingCode = null,
        public ?string $trackingUrl = null,
        public ?string $status = null,
        public ?string $message = null,
        public array $rawResponse = []
    ) {}
}
