<?php

namespace App\Observers;

use App\Models\CartItem;

class CartItemObserver
{
    /**
     * Handle the CartItem "created" event.
     */
    public function created(CartItem $cartItem): void
    {
        //
    }

    /**
     * Handle the CartItem "updated" event.
     */
    public function updated(CartItem $cartItem): void
    {
        
        $cartItem->load('category');
       
        $max_quantity = $cartItem->category->quantity;

        if($max_quantity < $cartItem->quantity){
            $cartItem->update(['quantity' => $max_quantity]);
        }

    }

    /**
     * Handle the CartItem "deleted" event.
     */
    public function deleted(CartItem $cartItem): void
    {
        //
    }

    /**
     * Handle the CartItem "restored" event.
     */
    public function restored(CartItem $cartItem): void
    {
        //
    }

    /**
     * Handle the CartItem "force deleted" event.
     */
    public function forceDeleted(CartItem $cartItem): void
    {
        //
    }
}
