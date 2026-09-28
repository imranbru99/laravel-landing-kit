<?php

namespace App\Filament\Resources\IncompleteOrders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncompleteOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('name')
                    ->searchable()
                    ->placeholder('N/A'),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),
                TextColumn::make('district.name')
                    ->label('District')
                    ->placeholder('N/A'),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lead' => 'warning',
                        'converted' => 'success',
                        'called' => 'info',
                        'abandoned' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('convertedOrder.order_number')
                    ->label('Converted Order')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Captured At')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'lead' => 'Lead',
                        'called' => 'Called',
                        'converted' => 'Converted',
                        'abandoned' => 'Abandoned',
                    ]),
            ])
            ->recordActions([
                Action::make('mark_called')
                    ->label('Mark Called')
                    ->icon('heroicon-o-phone')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'lead')
                    ->action(fn ($record) => $record->update(['status' => 'called'])),
                Action::make('mark_abandoned')
                    ->label('Mark Abandoned')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['lead', 'called']))
                    ->action(fn ($record) => $record->update(['status' => 'abandoned'])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
