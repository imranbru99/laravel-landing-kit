<?php

namespace App\Filament\Resources\IncompleteOrders;

use App\Filament\Resources\IncompleteOrders\Pages\CreateIncompleteOrder;
use App\Filament\Resources\IncompleteOrders\Pages\EditIncompleteOrder;
use App\Filament\Resources\IncompleteOrders\Pages\ListIncompleteOrders;
use App\Filament\Resources\IncompleteOrders\Schemas\IncompleteOrderForm;
use App\Filament\Resources\IncompleteOrders\Tables\IncompleteOrdersTable;
use App\Models\IncompleteOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class IncompleteOrderResource extends Resource
{
    protected static ?string $model = IncompleteOrder::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'E-Commerce';

    protected static ?string $navigationLabel = 'Incomplete Leads';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return IncompleteOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncompleteOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncompleteOrders::route('/'),
            'create' => CreateIncompleteOrder::route('/create'),
            'edit' => EditIncompleteOrder::route('/{record}/edit'),
        ];
    }
}
