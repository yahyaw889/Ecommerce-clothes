<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Special;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class orderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalOrderValue = OrderItems::query()
            ->selectRaw('SUM(total_amount) as total_order_value')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 1)
            ->first()
            ->total_order_value ?? 0;

        $totalSpecialValue = Special::query()
            ->selectRaw('SUM(price * quantity) as total_special_value')
            ->join('orders', 'orders.id', '=', 'specials.order_id')
            ->where('orders.status', 1)
            ->where('specials.status', 1)
            ->first()
            ->total_special_value ?? 0;
        return [
            Stat::make('  جمبع الطلبات علي الموقع', Order::count())
                ->description('  جمبع الطلبات علي الموقع ')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),


            Stat::make('الطلبات اللتي تم تسليمها', Order::query()->where('status', 1)->count())
                ->description('   الطلبات اللتي تم تسليمها')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),


            Stat::make('الطلبات التي لم يتم تسليمها الي الان', Order::query()->where('status', 0)->count())
                ->description(' عدد الطلبات اللتي لم تتم بعد')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),


                
             
            Stat::make(
                '  مجموع المبعات من  المنتجات المتوفره علي الموقع ',
                Number::currency($totalOrderValue)
            ),
            Stat::make(
                ' مجموع المبعات من  المنتجات     الخاصه ',
                Number::currency($totalSpecialValue)
            )



        ];
    }
}


