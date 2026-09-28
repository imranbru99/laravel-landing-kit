<?php

declare(strict_types=1);

namespace App\Filament\Resources\TrackingLogs;

use App\Filament\Resources\TrackingLogs\Pages\ListTrackingLogs;
use App\Models\TrackingLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class TrackingLogResource extends Resource
{
    protected static ?string $model = TrackingLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|UnitEnum|null $navigationGroup = 'Settings & System';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Tracking Logs & CAPI';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event_name')
                    ->label('Event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Purchase' => 'success',
                        'Lead' => 'warning',
                        'AddToCart', 'InitiateCheckout' => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'meta_capi' => 'primary',
                        'stape' => 'success',
                        'ga4' => 'warning',
                        'tiktok' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('event_id')
                    ->label('Event ID')
                    ->copyable()
                    ->fontFamily('mono')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        'queued' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->fontFamily('mono')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('M j, h:i:s A')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('platform')
                    ->options([
                        'meta_capi' => 'Meta CAPI',
                        'stape' => 'Stape Gateway',
                        'ga4' => 'GA4 MP',
                        'tiktok' => 'TikTok Events API',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                        'queued' => 'Queued',
                    ]),
            ])
            ->recordActions([
                Action::make('view_payload')
                    ->label('Payload')
                    ->icon('heroicon-o-code-bracket')
                    ->modalHeading('Event Payload & Response')
                    ->modalContent(fn (TrackingLog $record) => view('filament.modals.tracking-log-detail', ['log' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTrackingLogs::route('/'),
        ];
    }
}
