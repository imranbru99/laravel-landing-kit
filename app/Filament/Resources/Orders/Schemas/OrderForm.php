<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('Customer Information')->schema([
                        TextInput::make('customer_phone')
                            ->label('Phone Number (01XXXXXXXXX)')
                            ->required()
                            ->tel()
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (empty($state)) {
                                    return;
                                }
                                $normalized = llk_normalize_phone($state);
                                $set('customer_phone', $normalized);
                                $customer = Customer::where('phone', $normalized)->first();
                                if ($customer) {
                                    $set('customer_id', $customer->id);
                                    $set('customer_name', $customer->name);
                                    $set('customer_address', $customer->default_address);
                                    $set('district_id', $customer->district_id);
                                    $set('thana_id', $customer->thana_id);
                                }
                            }),

                        TextInput::make('customer_name')
                            ->label('Customer Full Name')
                            ->required(),

                        Textarea::make('customer_address')
                            ->label('Full Delivery Address')
                            ->required()
                            ->rows(2),

                        Grid::make(2)->schema([
                            Select::make('district_id')
                                ->label('District')
                                ->relationship('district', 'name_en')
                                ->searchable()
                                ->preload()
                                ->live(),

                            Select::make('thana_id')
                                ->label('Thana / Upazila')
                                ->relationship(
                                    name: 'thana',
                                    titleAttribute: 'name_en',
                                    modifyQueryUsing: fn ($query, Get $get) => $get('district_id') ? $query->where('district_id', $get('district_id')) : $query
                                )
                                ->searchable(),
                        ]),
                    ])->columnSpan(2),

                    Section::make('Order Summary & Status')->schema([
                        Select::make('status')
                            ->label('Order Status')
                            ->options(collect(OrderStatus::cases())->mapWithKeys(fn (OrderStatus $s) => [$s->value => $s->labelEn()])->all())
                            ->default(OrderStatus::Pending->value)
                            ->required(),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cod' => 'Cash On Delivery (COD)',
                                'bkash_manual' => 'bKash Manual',
                                'nagad_manual' => 'Nagad Manual',
                            ])
                            ->default('cod')
                            ->required(),

                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('unpaid'),

                        TextInput::make('payment_trx_id')
                            ->label('Payment TrxID'),

                        TextInput::make('delivery_charge')
                            ->label('Delivery Charge (৳)')
                            ->numeric()
                            ->default(60.00)
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),

                        TextInput::make('discount_amount')
                            ->label('Discount (৳)')
                            ->numeric()
                            ->default(0.00)
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),

                        TextInput::make('total_amount')
                            ->label('Grand Total (৳)')
                            ->numeric()
                            ->readOnly()
                            ->prefix('৳'),
                    ])->columnSpan(1),
                ]),

                Section::make('Order Items')->schema([
                    Repeater::make('items')
                        ->relationship('items')
                        ->schema([
                            Grid::make(4)->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('product_name', $product->name);
                                            $set('unit_price', $product->effective_price);
                                            $qty = (int) ($get('quantity') ?: 1);
                                            $set('total_price', $product->effective_price * $qty);
                                        }
                                    }),

                                TextInput::make('product_name')
                                    ->label('Product Title')
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(debounce: 300)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = (float) ($get('unit_price') ?: 0);
                                        $set('total_price', $price * (int) $state);
                                    }),

                                TextInput::make('unit_price')
                                    ->label('Unit Price (৳)')
                                    ->numeric()
                                    ->required()
                                    ->prefix('৳')
                                    ->live(debounce: 300)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $qty = (int) ($get('quantity') ?: 1);
                                        $set('total_price', ((float) $state) * $qty);
                                    }),
                            ]),
                        ])
                        ->columnSpanFull()
                        ->live()
                        ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),
                ]),
            ]);
    }

    public static function recalculateTotals(Set $set, Get $get): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;
        foreach ($items as $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $subtotal += ($qty * $price);
        }

        $delivery = (float) ($get('delivery_charge') ?? 0);
        $discount = (float) ($get('discount_amount') ?? 0);
        $total = max(0, $subtotal + $delivery - $discount);

        $set('subtotal', $subtotal);
        $set('total_amount', $total);
    }
}
