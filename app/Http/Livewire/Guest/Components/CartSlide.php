<?php

namespace App\Http\Livewire\Guest\Components;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CartSlide extends Component
{
    public Cart $cart;
    public Collection $cartItems;
    public $isShown = false;

    protected $listeners = [
        'show' => 'show',
        'refreshCart' => 'refresh',
    ];

    public function mount()
    {
        $this->cartItems = new Collection();
    }

    public function show()
    {
        $this->cart = $this->loadCart();
        $this->cartItems = $this->loadCartItems();
        $this->isShown = true;
    }

    public function refresh()
    {
        if (! $this->isShown) {
            return;
        }

        $this->cart = $this->loadCart();
        $this->cartItems = $this->loadCartItems();
    }

    public function loadCart(): Cart
    {
        $cart = $this->customer
            ? Cart::query()->firstOrCreate(['customer_id' => $this->customer->id])
            : Cart::query()->firstOrCreate(['session_id' => session()->getId()]);

        $cart->load([
            'items' => fn($query) => $query->orderBy('created_at', 'desc'),
            'items.product.media',
            'items.variant.media',
            'items.variant.variantAttributes.option',
            'items.variant.variantAttributes.optionValue',
            'items.category',
        ]);

        return $cart;
    }

    public function loadCartItems()
    {
        return $this->cart->items;
    }

    public function incrementItem($cartItemId): void
    {
        $item = $this->cart->items->find($cartItemId);

        if (! $item || ! $item->category || $item->quantity >= $item->category->quantity) {
            return;
        }

        $this->emit('addCart', [
            'product'  => $item->product_id,
            'variant'  => $item->variant_id,
            'category' => $item->category_id,
            'quantity' => $item->quantity + 1,
            'weight'   => $this->itemUnitWeight($item),
        ])->to('guest.product-list');
    }

    public function decrementItem($cartItemId): void
    {
        $item = $this->cart->items->find($cartItemId);

        if (! $item) {
            return;
        }

        if ($item->quantity <= 1) {
            $this->removeCartItem($cartItemId);
            return;
        }

        $this->emit('addCart', [
            'product'  => $item->product_id,
            'variant'  => $item->variant_id,
            'category' => $item->category_id,
            'quantity' => $item->quantity - 1,
            'weight'   => $this->itemUnitWeight($item),
        ])->to('guest.product-list');
    }

    public function removeCartItem($cartItemId): void
    {
        $item = $this->cart->items->find($cartItemId);
        $categoryId = $item?->category_id;

        $item?->delete();

        $this->refresh();

        $this->emit('refresh')->to('guest.components.header');
        $this->emit('refreshCart')->to('guest.product-list');

        if ($categoryId) {
            $this->emit('categoryReset', $categoryId);
        }
    }

    protected function itemUnitWeight($item): float
    {
        $variant = $item->variant;

        if (! $variant) {
            return 0;
        }

        return $variant->weight_unit === 'g'
            ? ($variant->weight_value / 1000)
            : $variant->weight_value;
    }

    public function getCustomerProperty(): \App\Models\Customer|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return \Auth::user();
    }

    public function render()
    {
        return view('livewire.guest.components.cart-slide');
    }
}