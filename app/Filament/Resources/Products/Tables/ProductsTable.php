<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Product $record): string => $record->sku ? "SKU: {$record->sku}" : ''),

                TextColumn::make('slug')
                    ->label('URL Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied to clipboard')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('regular_price')
                    ->label('Regular Price')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->money('BDT')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (Product $record): string => match (true) {
                        !$record->track_stock => 'info',
                        $record->stock_quantity <= 0 => 'danger',
                        $record->stock_quantity <= $record->low_stock_threshold => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (Product $record, $state): string => $record->track_stock ? (string) $state : 'Untracked'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('amber')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'draft' => 'Draft',
                        'archived' => 'Archived',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('builder')
                    ->label('Visual Builder')
                    ->icon('heroicon-o-paint-brush')
                    ->color('success')
                    ->url(fn (Product $record): string => route('admin.products.builder', $record))
                    ->openUrlInNewTab(),
                Action::make('preview')
                    ->label('View Page')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Product $record): string => url("/{$record->slug}"))
                    ->openUrlInNewTab(),
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
