<?php

namespace App\Observers;

use App\Models\Cart;

class CartObserver
{
    /**
     * Handle the Cart "created" event.
     */
    public function created(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "updated" event.
     */
    public function updated(Cart $cart): void
    {
        $cart->load('items');

        $cart->items()
        ->select(DB::raw('count(*) as category_count, category_id'))->groupBy('category_id')->get()
        ->map(function($item) use ($cart){
            if($item->category_count > 1 && $item->category->quantity < $item->category_count){
                $cart->items()->where('category_id', $item->category_id)->first()->delete();
            }
        });
        
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "restored" event.
     */
    public function restored(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "force deleted" event.
     */
    public function forceDeleted(Cart $cart): void
    {
        //
    }
}
