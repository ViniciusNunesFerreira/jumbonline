<?php

namespace App\Http\Livewire\Guest;

use App\Models\Cart;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use App\Models\PrisonUnit;

use Illuminate\Http\Request;

class ShoppingCart extends Component
{
    use SEOTools;

    public $prison = '';

    protected $listeners = [
        'refresh' => '$refresh',
    ];


    public function mount(Request $request)
    {
        $this->seo()->setTitle('Lista Jumbo');

        if(!$request->session()->has('prison')) {
            $this->redirect(route('guest.welcome'));
        }

       $this->prison = $request->session()->get('prison');
       
    }

    public function updateCartItemQuantity($cartItemId, $quantity)
    {
        $max_quantity = $this->cartItems->find($cartItemId)->category->quantity;

        if ($quantity < 1) {
            return $this->addError('cartItems.' . $cartItemId . '.quantity', __('A quantidade deve ser mínimo 1'));
        }

        if($max_quantity >= $quantity){
            $this->cartItems->find($cartItemId)->update(['quantity' => $quantity]);
        }else{
            return $this->addError('cartItems.' . $cartItemId . '.quantity', __('A quantidade máxima é: {{$max_quantity}} '));
        }
        

        $this->emit('refresh')->to('guest.components.header');
    }

    public function removeCartItem($cartItemId)
    {
        $this->cartItems->find($cartItemId)->delete();

        $this->emit('refresh')->self();

        $this->emit('refresh')->to('guest.components.header');
        $this->emit('refreshCart')->to('guest.components.category-products');
    }

    public function getCustomerProperty()
    {
        return \Auth::user();
    }

    public function getCartProperty(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $cart = $this->customer
            ? Cart::query()->firstOrCreate(['customer_id' => $this->customer->id])
            : Cart::query()->firstOrCreate(['session_id' => session()->getId()]);

        $cart->load([
            'items.category',
            'items.product.media',
            'items.variant.media',
            'items.variant.variantAttributes.option',
            'items.variant.variantAttributes.optionValue',
        ]);

        return $cart;
    }

    public function getCartItemsProperty()
    {
        return $this->cart->items;
    }

    public function render()
    {
        return view('livewire.guest.shopping-cart', [
            'cart' => $this->cart,
            'cartItems' => $this->cartItems,
        ])->layout('layouts.guest');
    }
}
