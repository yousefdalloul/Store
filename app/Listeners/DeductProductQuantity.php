<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeductProductQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        foreach ($order->products as $product) {
            $quantityToDeduct = $product->order_item->quantity;

            // Check if the available quantity is greater than or equal to the quantity to deduct
            if ($product->quantity >= $quantityToDeduct) {
                $product->decrement('quantity', $quantityToDeduct);
            } else {
                // Handle insufficient quantity (e.g., log an error, notify admin, etc.)
            }
        }
    }
}
