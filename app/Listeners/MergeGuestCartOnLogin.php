<?php

namespace App\Listeners;

use App\Models\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class MergeGuestCartOnLogin
{
    public function handle(Login $event): void
    {
        
        if ($event->guard !== 'customer') {
            return;
        }

        $customer = $event->user;
        $sessionId = session()->getId(); 

        $guestCart = Cart::query()
            ->where('session_id', $sessionId)
            ->whereNull('customer_id')
            ->first();

        if (! $guestCart) {
            return;
        }

        $customerCart = Cart::query()->firstOrCreate(['customer_id' => $customer->id]);

        if ($guestCart->id === $customerCart->id) {
            return;
        }

        DB::transaction(function () use ($guestCart, $customerCart) {
            foreach ($guestCart->items as $guestItem) {
                $existing = $customerCart->items()
                    ->where('product_id', $guestItem->product_id)
                    ->where('variant_id', $guestItem->variant_id)
                    ->first();

                if ($existing) {
                    // decisão assumida: soma quantidades em caso de conflito. Ajuste se preferir outra regra.
                    $existing->increment('quantity', $guestItem->quantity);
                    $guestItem->delete();
                } else {
                    $guestItem->update(['cart_id' => $customerCart->id]);
                }
            }

            $guestCart->delete();
        });
    }
}