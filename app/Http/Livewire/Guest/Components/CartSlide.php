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

        $newQuantity = $item->quantity + 1;
        $item->update(['quantity' => $newQuantity]);

        $this->syncAfterChange($item, $newQuantity);
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

        $newQuantity = $item->quantity - 1;
        $item->update(['quantity' => $newQuantity]);

        $this->syncAfterChange($item, $newQuantity);
    }

    public function removeCartItem($cartItemId): void
    {
        $item = $this->cart->items->find($cartItemId);
        $categoryId = $item?->category_id;

        $item?->delete();

        // Atualiza a coleção já carregada em memória, sem recarregar mídia/atributos do zero
        $this->cartItems = $this->cartItems->reject(fn($i) => $i->id == $cartItemId)->values();
        $this->cart->setRelation('items', $this->cartItems);

        $this->emit('refresh')->to('guest.components.header');
        $this->emitTo('guest.product-list', 'refreshCartSilently');

        if ($categoryId) {
            $this->emit('categoryReset', $categoryId);
        }
    }

    protected function syncAfterChange($item, int $newQuantity): void
    {
        // Atualiza a coleção local em memória — evita chamar loadCart() de novo
        $this->cart->setRelation(
            'items',
            $this->cartItems->map(function ($i) use ($item, $newQuantity) {
                if ($i->id === $item->id) {
                    $i->quantity = $newQuantity;
                }
                return $i;
            })
        );
        $this->cartItems = $this->cart->items;

        $this->emit('refresh')->to('guest.components.header');
        $this->emitTo('guest.product-list', 'refreshCartSilently');
        $this->emit('categorySync', $item->category_id, $item->product_id, $newQuantity);
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