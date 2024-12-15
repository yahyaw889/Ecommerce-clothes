<?php

namespace App\Observers;

use App\Models\OrderItems;

class ItemObserver
{
    /**
     * Handle the OrderItems "created" event.
     */


    /**
     * Handle the OrderItems "updated" event.
     */
    public function updating(OrderItems $orderItems): void
    {
        // $quantity = OrderItems::query()
        // ->where('product_id', $orderItems->product_id)
        // ->first()->quantity;

        // if($orderItems->quantity > $quantity)
        //     {
        //     $orderItems->product->increment('unit_sold', $orderItems->quantity - $quantity);
        // }elseif($orderItems->quantity < $quantity){
        //     $orderItems->product->decrement('unit_sold', $quantity - $orderItems->quantity );
        // }
    }

    /**
     * Handle the OrderItems "deleted" event.
     */
    public function deleting(OrderItems $orderItems): void
    {
        $orderItems->product->decrement('unit_sold', $orderItems->quantity);
        $orderItems->product->increment('quantity', $orderItems->quantity);
    }

    /**
     * Handle the OrderItems "restored" event.
     */
    public function restored(OrderItems $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItems "force deleted" event.
     */
    public function forceDeleted(OrderItems $orderItems): void
    {
        //
    }
}
