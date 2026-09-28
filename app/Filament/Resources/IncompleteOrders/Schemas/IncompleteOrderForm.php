<?php

namespace App\Filament\Resources\IncompleteOrders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IncompleteOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('name'),
                Textarea::make('address')
                    ->columnSpanFull(),
                Select::make('district_id')
                    ->relationship('district', 'id'),
                Select::make('thana_id')
                    ->relationship('thana', 'id'),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('product_variant_id')
                    ->numeric(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('status')
                    ->required()
                    ->default('new'),
                Select::make('converted_order_id')
                    ->relationship('convertedOrder', 'id'),
                TextInput::make('ip_address'),
                TextInput::make('utm_source'),
                TextInput::make('utm_medium'),
                TextInput::make('utm_campaign'),
            ]);
    }
}
