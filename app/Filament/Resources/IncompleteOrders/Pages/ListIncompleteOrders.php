<?php

namespace App\Filament\Resources\IncompleteOrders\Pages;

use App\Filament\Resources\IncompleteOrders\IncompleteOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIncompleteOrders extends ListRecords
{
    protected static string $resource = IncompleteOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
