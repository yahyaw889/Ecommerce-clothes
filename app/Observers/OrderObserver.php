<?php
namespace App\Observers;

use App\Models\Order;
use App\Models\Product;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order): void
    {
        $state = $order->status;

        if ($state) {
            foreach ($order->items as $item) {
                $item->product->increment('unit_sold', $item->quantity);  // Using the relationship
                $item->product->decrement('quantity', $item->quantity);  // Using the relationship
                $item->product->increment('views');  // Using the relationship
            }
        } else {
            foreach ($order->items as $item) {
                if ($item->product->unit_sold > 0) {  // Check to avoid negative unit_sold
                    $item->product->decrement('unit_sold', $item->quantity);
                    $item->product->increment('quantity', $item->quantity);
                }
            }
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
