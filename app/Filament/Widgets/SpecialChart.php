<?php

namespace App\Filament\Widgets;

use App\Models\Special; 
use Filament\Widgets\ChartWidget; 
use Flowframe\Trend\Trend;  
use Flowframe\Trend\TrendValue;  
 
class SpecialChart extends ChartWidget
{
    protected static ?string $heading = 'Special Sales Statistics';  
    protected static ?int $sort = 3;
    protected function getType(): string
    {
        return 'bar'; 
    }

    protected function getData(): array
    {
        $data = Trend::model(Special::class)
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
}
