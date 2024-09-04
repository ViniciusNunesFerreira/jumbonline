<?php

namespace App\Http\Livewire\Guest;

use Livewire\Component;
use Artesaos\SEOTools\Traits\SEOTools;
use App\Http\Livewire\Traits\MercadopagoPayment;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\CartItem;

use App\Enums\PaymentStatus;
use App\Events\OrderCreated;
use App\Settings\CheckoutSetting;

class Purchase extends Component
{
    use MercadopagoPayment;
    use SEOTools;

    public $currentTab = 'tabs-entrega';
    public Order $order;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        if ($this->cartItems->isEmpty()) return redirect()->route('guest.welcome');
        if ($this->checkout_settings->requires_login && ! auth()->check()) {
            return redirect()->route('login');
        }
        $this->order = new Order(['customer_email' => $this->customer?->email]);

        $this->createPreference();

        $this->seo()->setTitle(trans('Checkout'));
    }

    public function changeTab($current)
    {
        $this->currentTab = $current;

    }

    public function getCustomerProperty(): \App\Models\Customer|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return \Auth::user();
    }

    public function getCartProperty(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $cart = $this->customer
            ? Cart::query()->firstOrCreate(['customer_id' => $this->customer->id])
            : Cart::query()->firstOrCreate(['session_id' => session()->getId()]);

        $cart->load([
            'discounts',
            'items.discount',
            'items.product.media',
            'items.variant.media',
            'items.variant.variantAttributes.option',
            'items.variant.variantAttributes.optionValue',
        ])->loadCount('items');

        return $cart;
    }

    public function getCartItemsProperty()
    {
        return $this->cart->items;
    }

    public function getAvailablePaymentProvidersProperty(): Collection|array
    {
        return PaymentMethod::query()
            ->where('is_enabled', true)
            ->get();
    }

    public function getCheckoutSettingsProperty()
    {
        return app(CheckoutSetting::class);
    }

    public function render()
    {
        return view('livewire.guest.purchase',[
            'cart' => $this->cart,
            'cartItems' => $this->cartItems,
        ])->layout('layouts.guest');
    }
}
