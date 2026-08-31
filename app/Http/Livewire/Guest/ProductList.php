<?php

namespace App\Http\Livewire\Guest;

use Livewire\Component;
use App\Enums\ProductSaleChannel;
use App\Models\PrisonUnit;
use Artesaos\SEOTools\Traits\SEOTools;
use App\Models\Cart;
use App\Models\Product;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Cache;

class ProductList extends Component
{
    use SEOTools;

    public PrisonUnit $prison;
    public Product $product;
    public $prison_phone_format;
    public $perPage = 10;

    protected $listeners = [
        'refreshCart',
        'refreshCartSilently', 
        'addCart', 
        'updateCartItemQuantity', 
        'removeCategorySelection'
    ];

    public $weight_max = 12;

   //protected ?Cart $cachedCart = null;

    public function mount()
    {
        $endereco = "{$this->prison->cidade}/{$this->prison->uf}";

        $this->seo()->setTitle(
            $this->prison->seo_title ?: "Jumbo para o {$this->prison->name} | Jumbonline"
        );
        $this->seo()->setDescription(
            $this->prison->seo_description ?: "Envie o jumbo autorizado para o {$this->prison->name}, em {$endereco}. Itens dentro das normas da unidade, entrega rápida e direta. Monte o jumbo agora."
        );
        $this->seo()->setCanonical(route('guest.products.list', $this->prison->slug));

        if ($this->prison->phone) {
            $prison_phone = new PhoneNumber($this->prison->phone, 'BR');
            $this->prison_phone_format = $prison_phone->formatNational();
        }

        $this->refreshCart();
    }

    public function getCartProperty(): Cart
    {
        $cart = $this->customer
            ? Cart::firstOrCreate(['customer_id' => $this->customer->id])
            : Cart::firstOrCreate(['session_id' => session()->getId()]);

        return $cart->loadMissing('items.variant');
    }

    public function getCartCategoriesProperty()
    {
        return $this->cart->items->keyBy('category_id')->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
            ];
        })->toArray();
    }

    public function getWeightProperty()
    {
        return $this->cart->weight;
    }

    public function getSubTotalProperty()
    {
        return $this->cart->subtotal;
    }



    public function refreshCart()
    {
    
        $this->emitTo('guest.components.header', 'refreshCart');
        $this->emitTo('guest.components.cart-slide', 'refreshCart');
    }

    public function refreshCartSilently()
    {
        
       
    }

    public function openCart()
    {
        $this->emitTo('guest.components.cart-slide', 'show');
    }

    public function getCustomerProperty(): \App\Models\Customer|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return \Auth::user();
    }

   public function addCart(array $items)
    {
        $cart = $this->cart;

        $otherItemsWeight = 0;
        foreach ($cart->items as $item) {
            if ($item->category_id != $items['category']) {
                $variant = $item->variant;
                if ($variant) {
                    $unitWeight = $variant->weight_unit === 'g' 
                        ? ($variant->weight_value / 1000) 
                        : $variant->weight_value;
                    $otherItemsWeight += ($unitWeight * $item->quantity);
                }
            }
        }

        $newItemTotalWeight = $items['weight'] * $items['quantity'];
        $projectedWeight = $otherItemsWeight + $newItemTotalWeight;

        if ($projectedWeight <= $this->weight_max) {
            $cart->items()->updateOrCreate(
                ['category_id' => $items['category']],
                [
                    'product_id' => $items['product'],
                    'variant_id' => $items['variant'],
                    'quantity'   => $items['quantity'],
                ]
            );

            
            $cart->unsetRelation('items');

            $this->refreshCart();
            
            $this->emit("categorySync.{$items['category']}", $items['product'], $items['quantity']);

        } else {
            $this->emit("categoryReset.{$items['category']}");

            $this->dispatchBrowserEvent('notify', [
                'message' => 'Peso máximo do Jumbo excedido (' . $this->weight_max . ' kg)!'
            ]);
        }
    }

    public function removeCategorySelection($categoryId)
    {
        $this->cart->items()->where('category_id', $categoryId)->delete();
        
        $this->cart->unsetRelation('items');
        
        $this->refreshCart();

        $this->emit('categoryReset', $categoryId);
    }


    public function getRowsProperty()
    {
        $page = request()->get('page', 1);

        return Cache::remember(
            "prison-catalog:{$this->prison->id}:page:{$page}",
            now()->addMinutes(10),
            function () {
                return $this->prison->collections()->with([
                    'categoriesPublished' => function ($query) {
                        $query->whereHas('products', function ($q) {
                            $q->where('sales_channel', '!=', ProductSaleChannel::BALCAO->name);
                        });
                    },
                    'categoriesPublished.products' => function ($query) {
                        $query->where('sales_channel', '!=', ProductSaleChannel::BALCAO->name);
                    },
                    'categoriesPublished.products.variants'
                ])->paginate($this->perPage);
            }
        );
    }

    public function render()
    {
        return view('livewire.guest.product-list', [
            'collections' => $this->rows,
            'weight' => $this->weight,
            'cartCategories' => $this->cartCategories,
            'subTotal' => $this->subTotal,
        ])->layout('layouts.guest');
    }
}