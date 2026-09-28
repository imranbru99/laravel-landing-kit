<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Orders by Lifecycle Status';

    protected function getData(): array
    {
        $statuses = [
            OrderStatus::Pending->value => ['label' => 'Pending', 'color' => '#f59e0b'],
            OrderStatus::Confirmed->value => ['label' => 'Confirmed', 'color' => '#10b981'],
            OrderStatus::Shipped->value => ['label' => 'Shipped', 'color' => '#3b82f6'],
            OrderStatus::Delivered->value => ['label' => 'Delivered', 'color' => '#059669'],
            OrderStatus::Cancelled->value => ['label' => 'Cancelled', 'color' => '#ef4444'],
            OrderStatus::Returned->value => ['label' => 'Returned', 'color' => '#dc2626'],
        ];

        $labels = [];
        $data = [];
        $bgColors = [];

        foreach ($statuses as $statusKey => $meta) {
            $labels[] = $meta['label'];
            $data[] = Order::where('status', $statusKey)->count();
            $bgColors[] = $meta['color'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'backgroundColor' => $bgColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
