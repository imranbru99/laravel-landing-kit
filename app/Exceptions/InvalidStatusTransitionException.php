<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\OrderStatus;
use Exception;

class InvalidStatusTransitionException extends Exception
{
    public static function make(OrderStatus $from, OrderStatus $to): self
    {
        return new self("Cannot transition order from status [{$from->value}] to [{$to->value}].");
    }
}
