<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
             Stat::make('كل المنتجات', Product::query()->count())         
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),

            Stat::make('الفئات غير المفعلة', Category::query()->where('status', 0)->count())
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
           
            
            Stat::make('الماركات غير المفعلة', Brand::query()->where('status', 0)->count())
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
           
        ];
    }
}
