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
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Http\Request;

use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\PrisonUnit;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\ShippingZone;
use App\Models\ShippingZoneCountry;
use App\Models\ShippingZoneRate;

use App\Enums\PaymentStatus;
use App\Events\OrderCreated;
use App\Settings\CheckoutSetting;

class Purchase extends Component
{
    use MercadopagoPayment;
    use SEOTools;


    public $shippingMethod;
    public $step = 1;
    public $currentTab = 'tabs-entrega';
    public $paymentMethod;
    public $session_prison;

    public $state = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'phone_country' => '',
    ];


    protected $listeners = ['refresh' => '$refresh', 'changeTab'];


    protected function messages(): array
    {
        return [
            'state.name.required' => 'Informe seu nome completo para contato.',
            'state.email.required' => 'Informe um email válido para contato.',
            'state.email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'state.email.unique' => 'O email informado já está em uso.',
            'state.phone.required' => 'Informe um telefone válido para contato',
            'state.phone_country.required' => 'Código do país é necessário',

            'cartItems.*.quantity.required' => __('Quantidade é requerido'),
            'cartItems.*.quantity.numeric' => __('Quantidade precisa ser um inteiro válido'),
            'cartItems.*.quantity.min' => __('A quantidade deve ser pelo menos 1'),
            'order.customer_email' => __('Por favor insira seu endereço de e-mail'),
        ]; 
    }  

    public function mount(Request $request)
    {
        if ($this->cartItems->isEmpty()) return redirect()->route('guest.welcome');
        if ($this->checkout_settings->requires_login && ! auth()->check()) {
            return redirect()->route('login');
        }
        if(!$request->session()->has('prison')) {
            $this->redirect(route('guest.welcome'));
        }

       $this->session_prison = $request->session()->get('prison');

       $phone_format = '';
       if(optional($this->customer)->phone){
            $phone_format = new PhoneNumber($this->customer->phone, $this->customer->phone_country);
       }
       
        $this->state = [
            'name' => $this->customer?->name,
            'email' => $this->customer?->email,
            'phone' => $this->customer->phone ? $phone_format->formatNational() : '',
            'phone_country' => $this->customer?->phone_country ? $this->customer->phone_country : 'BR',
        ];


      //  $this->createPreference();

        $this->seo()->setTitle(trans('Checkout'));
    }


    public function changeTab($current)
    {
        $this->currentTab = $current;

        if($current == 'tabs-entrega'){
            $this->step = 1;
        }
        
        if($current == 'tabs-detento'){
            $this->step = 2;
        }

        if($current == 'tabs-pagamento'){

            if($this->step > 0){
                $this->placeOrder();
                $this->step = 3;
            }

        }

        

    }



    public function saveCustomer()
    {
       
        $this->state['phone'] = new PhoneNumber($this->state['phone'], $this->state['phone_country']);

        $this->validate([
            'state.name' => ['required'],
            'state.email' => ['required', 'email', Rule::unique('customers', 'email')->ignore($this->customer?->id)],
            'state.phone' => ['required', Rule::phone()->country($this->state['phone_country'])],
            'state.phone_country' => ['required']
        ]);

        $this->customer->update([
            'name' => $this->state['name'],
            'email' => $this->state['email'],
            'phone' => $this->state['phone'],
            'phone_country' => $this->state['phone_country']
        ]);

        $this->notify(trans('Perfil atualizado com sucesso.'));

        $this->changeTab('tabs-detento');
        $this->step = 2;
    }

    public function getAvailableCountriesProperty(): Collection|array
    {
        return Country::query()
            ->select( 'name', 'phonecode', 'emoji', 'iso2')
            ->get();   
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

    public function getPrisonUnitProperty(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $prisonUnit = null;

        if(!empty($this->session_prison)) {
            $prisonUnit =  PrisonUnit::query()->where('slug', $this->session_prison)->first(); 
        };

        return $prisonUnit;

    }

    public function getOrderProperty(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return Order::query()->firstOrNew(['customer_email' => $this->customer?->email], 
            ['prison_unit_id' => $this->prisonUnit->id]
        );

    }

    public function findOrCreateCustomer()
    {
        return Customer::query()->firstOrCreate([
            'email' => $this->order->customer_email,
        ], [
            'name' => $this->state->name,
            'password' => Hash::make(Str::random(40)),
        ]);
    }



    protected function placeOrder()
    {
        \Log::debug($this->order);

        //valida se existe consumidor
        $customer = $this->customer ? $this->customer : $this->findOrCreateCustomer();



        $customer->load(['detentos', 'visitantes']);

        $this->order->customer_id = $customer->id;
        $this->order->customer_email = $customer->email;

        $this->order->payment_method_id = $this->availablePaymentProviders->firstWhere('identifier', 'mercadopago')->id;
        $this->order->payment_status = PaymentStatus::UNPAID;

        //valida se existe detento e visitante cadastrado

        if($customer->detentos()->count() == 0 || $customer->visitantes()->count() == 0){
            $this->notify(trans('Reveja as Informações de Detento ou Visitante'));
           return  $this->changeTab('tabs-detento');
        }


        $this->order->detento_id = $customer->detentos()->first()->id;
        $this->order->visitante_id =  $customer->visitantes()->first()->id;

        //recupera e atualiza ordem

        $this->order->save();

        $this->cartItems->each(function (CartItem $item) {
            $orderItem = $this->order->orderItems()->create([
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'name' => $item->product->name,
                'price' => $item->variant->price,
                'quantity' => $item->quantity,
            ]);

            if ($item->discount) {
                $this->order->orderDiscounts()->create([
                    'order_item_id' => $orderItem->id,
                    'code' => $item->discount->code,
                    'type' => $item->discount->type,
                    'amount' => $item->discount->amount,
                ]);
            }
        });

        $this->cart->items()->delete();

        $this->cart->discounts()->delete();

        $this->step = 0;

        OrderCreated::dispatch($this->order);
        
        $this->emit('refresh');

    }


    public function render()
    {
        return view('livewire.guest.purchase',[
            'cart' => $this->cart,
            'cartItems' => $this->cartItems,
            'prisonUnit' => $this->prisonUnit
        ])->layout('layouts.guest');
    }
}
