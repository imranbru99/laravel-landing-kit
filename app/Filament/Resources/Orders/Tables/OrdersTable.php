<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\Courier\CourierManager;
use App\Services\OrderStatusStateMachine;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order No')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Order number copied'),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn (Order $record): string => $record->customer_phone),

                TextColumn::make('district.name_bn')
                    ->label('Area')
                    ->default(fn (Order $record): string => $record->district?->name_en ?? '—')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => $state->color()),

                TextColumn::make('total_amount')
                    ->label('Total BDT')
                    ->money('BDT')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cod' => 'slate',
                        'bkash_manual' => 'rose',
                        'nagad_manual' => 'amber',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                TextColumn::make('courier_driver')
                    ->label('Courier')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : 'None')
                    ->description(fn (Order $record): string => $record->courier_tracking_id ?? ''),

                TextColumn::make('created_at')
                    ->label('Placed At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(fn (OrderStatus $s) => [$s->value => $s->labelEn()])->all()),
                SelectFilter::make('payment_method')
                    ->options([
                        'cod' => 'Cash On Delivery',
                        'bkash_manual' => 'bKash Manual',
                        'nagad_manual' => 'Nagad Manual',
                    ]),
            ])
            ->recordActions([
                Action::make('change_status')
                    ->label('Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->form([
                        Select::make('new_status')
                            ->label('New Order Status')
                            ->options(function (Order $record) {
                                return collect($record->status->allowedTransitions())
                                    ->mapWithKeys(fn (OrderStatus $s) => [$s->value => $s->labelEn().' ('.$s->labelBn().')'])
                                    ->all();
                            })
                            ->required(),
                        Textarea::make('notes')
                            ->label('Transition Reason / Notes'),
                    ])
                    ->action(function (Order $record, array $data, OrderStatusStateMachine $stateMachine): void {
                        $target = OrderStatus::from($data['new_status']);
                        try {
                            $stateMachine->transition($record, $target, auth()->user(), $data['notes'] ?? null);
                            Notification::make()
                                ->title('Status Updated')
                                ->body("Order #{$record->order_number} transitioned to {$target->labelEn()}.")
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Transition Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('send_courier')
                    ->label('Send to Courier')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Order $record, CourierManager $couriers): void {
                        $driver = $couriers->driver($record->courier_driver ?: 'manual');
                        $res = $driver->sendOrder($record);

                        if ($res->success) {
                            $record->update([
                                'courier_consignment_id' => $res->consignmentId,
                                'courier_tracking_id' => $res->trackingCode,
                                'courier_status' => $res->status,
                                'courier_sent_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Dispatched to Courier')
                                ->body("Consignment ID: {$res->consignmentId}")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Courier Dispatch Failed')
                                ->body($res->message)
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Order $record): string => route('admin.orders.invoice', $record))
                    ->openUrlInNewTab(),

                Action::make('packing_slip')
                    ->label('Slip')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->url(fn (Order $record): string => route('admin.orders.packing-slip', $record))
                    ->openUrlInNewTab(),

                Action::make('sticker')
                    ->label('Sticker')
                    ->icon('heroicon-o-tag')
                    ->url(fn (Order $record): string => route('admin.orders.courier-sticker', $record))
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
