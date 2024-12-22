<?php
namespace App\Observers;

use App\Models\Order;
use App\Models\Product;
use Filament\Notifications\Notification;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order): void
{
    $state = $order->status;

    try {
        foreach ($order->items as $item) {
            $product = $item->product;

            if (!$product) {
                throw new \Exception("Product not found for item {$item->id}");
            }

            if ($state) {
                // Ensure there is enough stock before decrementing
                if ($product->quantity >= $item->quantity) {
                    $product->increment('unit_sold', $item->quantity);
                    $product->decrement('quantity', $item->quantity);
                    $product->increment('views');
                } else {
                    throw new \Exception("Insufficient stock for product {$product->name}");
                }
            } else {
                // Avoid decrementing below zero
                if ($product->unit_sold >= $item->quantity) {
                    $product->decrement('unit_sold', $item->quantity);
                    $product->increment('quantity', $item->quantity);
                } else {
                    throw new \Exception("Cannot decrement unit_sold below zero for product {$product->id}");
                }
            }
        }
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() === '23000') {
            Notification::make()
                ->title('خطأ في تحديث الطلب')
                ->body('الكمية غير متاحة أو يوجد مشكلة في بيانات المنتج.')
                ->danger()
                ->send();
        } else {
            throw $e;
        }
    } catch (\Exception $e) {
        Notification::make()
            ->title('خطأ')
            ->body($e->getMessage())
            ->danger()
            ->send();
    }
}

    

    public function deleting(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product->unit_sold > 0) {  // Check to avoid negative unit_sold
            $item->product->decrement('unit_sold', $item->quantity);
            }
        }

    }
}
