<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomersTable
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
                    ->sortable(),
                TextColumn::make('district.name')
                    ->label('District')
                    ->sortable(),
                TextColumn::make('thana.name')
                    ->label('Thana')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_orders')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('total_spent')
                    ->formatStateUsing(fn ($state) => '৳' . number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('success_rate')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 1) . '%')
                    ->badge()
                    ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger'))
                    ->sortable(),
                IconColumn::make('is_blocked')
                    ->label('Blocked')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('toggle_block')
                    ->label(fn ($record) => $record->is_blocked ? 'Unblock' : 'Block')
                    ->icon(fn ($record) => $record->is_blocked ? 'heroicon-o-check-circle' : 'heroicon-o-no-symbol')
                    ->color(fn ($record) => $record->is_blocked ? 'success' : 'danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_blocked' => !$record->is_blocked]);
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
