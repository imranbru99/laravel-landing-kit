<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::latest()->limit(5))
            ->heading('Recent Orders')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('customer_phone')
                    ->label('Phone'),

                TextColumn::make('district.name_en')
                    ->label('District')
                    ->placeholder('Dhaka'),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('BDT'),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, h:i A'),
            ])
            ->paginated(false);
    }
}
