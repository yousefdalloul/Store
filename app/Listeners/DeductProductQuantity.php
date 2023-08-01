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
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        try {
            //عملية الخصم من المنتجات :
            //UPDATE products SET quantity = quantity - 1

            foreach ($order->products as $product) {
                $product->decrement('quantity', $product->order_item->quantity);

                //        foreach (Cart::get() as $item){
                //            Product::where('id','=',$item->product_id)
                //            ->update([
                //                'quantity'=> DB::row("quantity - ($item->quantity)")
                //            ]);
                //
                //        }
            }
        } catch (Throwable $e){


        }
    }
}
