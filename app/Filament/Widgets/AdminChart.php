<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
 use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AdminChart extends ChartWidget
{
    protected static ?string $heading = 'Orders';
    protected static ?int $sort = 3;
    protected static string $color = 'success';  
    protected static ?string $maxWidth = 'sm';  
    protected function getMaxHeight(): ?string
    {
        return '300px';  
    }
    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    
    protected function getType(): string
    {
       return 'line';
    }
}
