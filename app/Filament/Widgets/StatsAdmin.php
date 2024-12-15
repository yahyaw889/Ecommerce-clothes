<?php

namespace App\Filament\Widgets;

use App\Models\LoginUser;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Special;
use App\Models\User;
use App\Models\Views;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsAdmin extends BaseWidget
{
    protected static ?int $sort = 2;

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

        $siteViews = Views::first()?->views ?? 0;

        return [
            Stat::make('الملاك', User::query()->count())
                ->description('عدد المسجلين  لوحه التحكم ')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('المستخدمين', LoginUser::query()->count())
                ->description('عدد المسجلين في الموقع ')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('جميع الطلبات على الموقع', Order::count())
                ->description('جميع الطلبات على الموقع')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('الطلبات التي لم يتم تسليمها', Order::query()->where('status', 0)->count())
                ->description('عدد الطلبات التي لم تتم بعد')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

            Stat::make('الطلبات التي تم تسليمها', Order::query()->where('status', 1)->count())
                ->description('الطلبات التي تم تسليمها')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('منتجات المتاحة', Product::query()->where('status', 1)->count())
                ->description('عدد المنتجات المتاحة على الموقع')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('منتجات غير المتاحة', Product::query()->where('status', 0)->count())
                ->description('عدد المنتجات غير المتاحة على الموقع')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

            Stat::make('زيارات الموقع', $siteViews)
                ->description('عدد الزيارات على الموقع')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('عدد المنتجات الخاصة', Special::count())
                ->description('عدد المنتجات الخاصة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('سعر جميع الطلبات', Number::currency($totalOrderValue + $totalSpecialValue))
                ->description('إجمالي سعر الطلبات')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('سعر جميع المنتجات الخاصة', Number::currency($totalSpecialValue))
                ->description('إجمالي سعر المنتجات الخاصة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
