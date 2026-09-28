<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\IncompleteOrder;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = (float) Order::whereDate('created_at', today())
            ->whereIn('status', [OrderStatus::Confirmed, OrderStatus::Delivered, OrderStatus::Paid])
            ->sum('total_amount');

        $leadsCount = IncompleteOrder::where('status', 'lead')->count();

        $totalOrdersCount = Order::count();
        $deliveredOrdersCount = Order::where('status', OrderStatus::Delivered)->count();
        $deliveryRate = $totalOrdersCount > 0 ? round(($deliveredOrdersCount / $totalOrdersCount) * 100, 1) : 100;

        return [
            Stat::make('Today Orders', (string) $todayOrders)
                ->description('Orders placed today')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Today Revenue', llk_currency($todayRevenue))
                ->description('Confirmed/Delivered revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Incomplete Leads', (string) $leadsCount)
                ->description('Abandoned checkout leads')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),

            Stat::make('Delivery Success Rate', "{$deliveryRate}%")
                ->description("{$deliveredOrdersCount} of {$totalOrdersCount} delivered")
                ->descriptionIcon('heroicon-m-truck')
                ->color($deliveryRate >= 80 ? 'success' : 'danger'),
        ];
    }
}
