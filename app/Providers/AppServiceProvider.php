<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Observers\ItemObserver;
use App\Observers\OrderObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the OrderObserver for the Order model
        Order::observe(OrderObserver::class);
        OrderItems::observe(ItemObserver::class);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });
    }

}
