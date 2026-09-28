<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product Information')
                    ->tabs([
                        Tabs\Tab::make('Basic Details')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->label('Product Name')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('slug')
                                        ->label('URL Slug')
                                        ->helperText('Auto-generated if left blank')
                                        ->unique(ignoreRecord: true),
                                    TextInput::make('sku')
                                        ->label('SKU / Code')
                                        ->placeholder('e.g. LLK-SHIRT-001'),
                                    Select::make('categories')
                                        ->label('Categories')
                                        ->relationship('categories', 'name')
                                        ->multiple()
                                        ->preload(),
                                    TextInput::make('video_url')
                                        ->label('Product Video URL (YouTube / Vimeo / Direct)')
                                        ->url(),
                                    Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'active' => 'Active (Published)',
                                            'draft' => 'Draft',
                                            'archived' => 'Archived',
                                        ])
                                        ->default('active')
                                        ->required(),
                                ]),
                                Textarea::make('short_description')
                                    ->label('Short Hook / Highlights')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                RichEditor::make('long_description')
                                    ->label('Detailed Product Description')
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Pricing & Stock')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Section::make('Pricing')->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('regular_price')
                                            ->label('Regular Price (৳)')
                                            ->numeric()
                                            ->required()
                                            ->prefix('৳')
                                            ->default(0.00),
                                        TextInput::make('sale_price')
                                            ->label('Sale / Offer Price (৳)')
                                            ->numeric()
                                            ->prefix('৳'),
                                        TextInput::make('cost_price')
                                            ->label('Cost Price (৳ - For Profit Reports)')
                                            ->numeric()
                                            ->prefix('৳'),
                                    ]),
                                    Grid::make(2)->schema([
                                        DateTimePicker::make('sale_start_at')->label('Sale Starts At'),
                                        DateTimePicker::make('sale_end_at')->label('Sale Ends At'),
                                    ]),
                                ]),
                                Section::make('Inventory Tracking')->schema([
                                    Grid::make(4)->schema([
                                        Toggle::make('track_stock')
                                            ->label('Track Inventory')
                                            ->default(true),
                                        TextInput::make('stock_quantity')
                                            ->label('Available Stock')
                                            ->numeric()
                                            ->default(100),
                                        TextInput::make('low_stock_threshold')
                                            ->label('Low Stock Warning')
                                            ->numeric()
                                            ->default(5),
                                        Toggle::make('allow_backorder')
                                            ->label('Allow Backorders')
                                            ->default(false),
                                    ]),
                                    Toggle::make('is_featured')
                                        ->label('Featured Product')
                                        ->default(false),
                                ]),
                            ]),

                        Tabs\Tab::make('Gallery & Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Repeater::make('images')
                                    ->relationship('images')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            FileUpload::make('image_path')
                                                ->label('Product Image')
                                                ->image()
                                                ->directory('products')
                                                ->required(),
                                            TextInput::make('alt_text')
                                                ->label('Alt Text'),
                                            Toggle::make('is_primary')
                                                ->label('Primary / Cover Image')
                                                ->default(false),
                                        ]),
                                    ])
                                    ->orderColumn('sort_order')
                                    ->columnSpanFull()
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make('Variants')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Repeater::make('variants')
                                    ->relationship('variants')
                                    ->schema([
                                        Grid::make(4)->schema([
                                            TextInput::make('name')
                                                ->label('Variant Name (e.g. Size XL - Blue)')
                                                ->required(),
                                            TextInput::make('sku')
                                                ->label('Variant SKU'),
                                            TextInput::make('price')
                                                ->label('Price (৳)')
                                                ->numeric()
                                                ->prefix('৳')
                                                ->required(),
                                            TextInput::make('sale_price')
                                                ->label('Sale Price (৳)')
                                                ->numeric()
                                                ->prefix('৳'),
                                            TextInput::make('stock_quantity')
                                                ->label('Stock')
                                                ->numeric()
                                                ->default(10),
                                            Toggle::make('is_active')
                                                ->label('Available')
                                                ->default(true),
                                        ]),
                                    ])
                                    ->columnSpanFull()
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make('Offers & Discounts')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Repeater::make('offers')
                                    ->relationship('offers')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            Select::make('type')
                                                ->label('Offer Type')
                                                ->options([
                                                    'quantity_tier' => 'Quantity Tier (Buy 2+ get X off)',
                                                    'bundle' => 'Bundle Offer',
                                                    'order_bump' => 'Order Bump (Checkout Add-on)',
                                                    'free_delivery' => 'Free Delivery Offer',
                                                ])
                                                ->required(),
                                            TextInput::make('name')
                                                ->label('Offer Label (e.g. 2 Pcs Package)')
                                                ->required(),
                                            TextInput::make('title')
                                                ->label('Badge / Subtitle (e.g. Save 100 Tk)'),
                                            Select::make('discount_type')
                                                ->label('Discount Type')
                                                ->options([
                                                    'fixed' => 'Fixed Amount (৳)',
                                                    'percent' => 'Percentage (%)',
                                                ])
                                                ->default('fixed'),
                                            TextInput::make('discount_amount')
                                                ->label('Discount Amount')
                                                ->numeric()
                                                ->default(0.00),
                                            TextInput::make('min_quantity')
                                                ->label('Minimum Quantity')
                                                ->numeric()
                                                ->default(1),
                                            Toggle::make('is_active')
                                                ->label('Active Offer')
                                                ->default(true),
                                        ]),
                                    ])
                                    ->columnSpanFull()
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make('SEO & Social')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('seo_title')
                                        ->label('SEO Meta Title')
                                        ->maxLength(70),
                                    TextInput::make('canonical_url')
                                        ->label('Canonical URL'),
                                ]),
                                Textarea::make('seo_description')
                                    ->label('SEO Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->columnSpanFull(),
                                FileUpload::make('seo_og_image')
                                    ->label('Social Share (OG) Image')
                                    ->image()
                                    ->directory('seo'),
                                Toggle::make('noindex')
                                    ->label('No-Index (Hide from Search Engines)')
                                    ->default(false),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
