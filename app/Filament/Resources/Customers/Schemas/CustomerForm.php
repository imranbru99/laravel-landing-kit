<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Textarea::make('default_address')
                    ->columnSpanFull(),
                Select::make('district_id')
                    ->relationship('district', 'id'),
                Select::make('thana_id')
                    ->relationship('thana', 'id'),
                TextInput::make('total_orders')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_spent')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivered_orders_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('returned_orders_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('cancelled_orders_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('success_rate')
                    ->required()
                    ->numeric()
                    ->default(100.0),
                Toggle::make('is_blocked')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
