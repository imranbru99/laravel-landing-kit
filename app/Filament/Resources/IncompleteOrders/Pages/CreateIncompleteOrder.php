<?php

namespace App\Filament\Resources\IncompleteOrders\Pages;

use App\Filament\Resources\IncompleteOrders\IncompleteOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncompleteOrder extends CreateRecord
{
    protected static string $resource = IncompleteOrderResource::class;
}
