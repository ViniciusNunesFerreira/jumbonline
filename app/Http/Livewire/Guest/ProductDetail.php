<?php

namespace App\Http\Livewire\Guest;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Variant;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use App\Enums\ProductType;

class ProductDetail extends Component
{
    use SEOTools;

    public Product $product;
    public Variant $variant;

    public int $minQuantity = 1;
    public int $maxQuantity = 1;

    public $subTotal = 0;
    public $weight_max = 12;
    public $weight = 0;

    public array $selectedOptionValues;

    public array $addToCart = [
        'product' => null,
        'variant' => null,
        'quantity' => 1,
    ];

    public string $variantQuery = '';

    protected $queryString = ['variantQuery' => ['except' => '', 'as' => 'variant']];

   
    public function mount()
    {

        $this->product
            ->load([
                'media',
                'reviews' => fn($query) => $query->whereNotNull('published_at'),
                'reviews.customer.media',
            ])
            ->loadCount([
                'media'
            ]);


        abort_unless($this->product->is_active, 404);
        abort_unless($this->product->type === ProductType::KIT, 404);
        abort_unless($this->productVariants->count(), 500);


        if ($this->productVariants->count() > 1) {
            if ($this->variantQuery != '') {
                $variant = $this->productVariants->where('id', $this->variantQuery)->first();
                if ($variant) {
                    $this->variant = $variant;
                } else {
                    return redirect()->route('guest.products.show', $this->product);
                }
            } else {
                $this->variant = $this->productVariants->first();
            }
            $this->variantQuery = $this->variant->id;
        } else {
            $this->variant = $this->productVariants->first();
        }

        $this->addToCart['product'] = $this->product->id;
        $this->addToCart['variant'] = $this->variant->id;
        $this->addToCart['category'] =  $this->product->category()->id;


        $this->weight = $this->cart->weight;
        $this->subTotal = $this->cart->subTotal;


        $this->maxQuantity = $this->variant->stock_value > 0 ? $this->variant->stock_value : $this->maxQuantity;

        $this->selectedOptionValues = $this->variant->variantAttributes->pluck('option_value_id')->toArray();

        $this->setRecentlyViewedProduct();

        $this->seo()->setTitle($this->product->seo_title ?: $this->product->name);

        $this->seo()->setDescription($this->product->seo_description ?: $this->product->excerpt);

        $this->seo()->opengraph()->addImage($this->product->getFirstMediaUrl('gallery'), [
            'height' => 600,
            'width' => 600,
        ]);
    }


    public function addToCart()
    {
        
        if( $this->cart->weight < $this->weight_max){

            $this->cart->items()->updateOrCreate([
                'product_id' => $this->addToCart['product'],
                'variant_id' => $this->addToCart['variant'],
            ], [
                'quantity' => $this->addToCart['quantity'],
                'category_id' => $this->addToCart['category'],
            ]);

            
            $this->emit('show')->to('guest.components.cart-slide');
            
        }else{
            $this->notify(trans('Peso máximo do Jumbo: '.$this->weight_max.' kg!'));
        }

        $this->emit('refresh')->to('guest.components.header');

    }

    public function getCustomerProperty(): \App\Models\Customer|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return \Auth::user();
    }

    public function getCartProperty(): \App\Models\Cart|\Illuminate\Database\Eloquent\Model
    {
        return $this->customer
            ? Cart::query()->firstOrCreate(['customer_id' => $this->customer->id])
            : Cart::query()->firstOrCreate(['session_id' => session()->getId()]);
    }



    public function getRecentlyViewedProductsProperty()
    {
        if (session()->has('recently_viewed_products')) {

            $recentlyViewedProductKeys = session()->get('recently_viewed_products');

            return Product::query()
                ->with('media')
                ->find($recentlyViewedProductKeys)
                ->sortBy(function ($order) use ($recentlyViewedProductKeys) {
                    return array_search($order['id'], $recentlyViewedProductKeys);
                })
                ->values()
                ->where('type', '===', ProductType::KIT)
                ->all();
        }

        return [];
    }

    public function getProductVariantsProperty()
    {
        $this->product->load('variants.variantAttributes');

        return $this->product->variants;
    }

    public function setRecentlyViewedProduct()
    {
        $recentlyViewedProducts = session()->has('recently_viewed_products') ? collect(session()->pull('recently_viewed_products')) : collect();

        if ($recentlyViewedProducts->count() > 3 && !$recentlyViewedProducts->contains($this->product->getKey())) $recentlyViewedProducts->pop();

        $recentlyViewedProducts->prepend($this->product->getKey());

        session()->put('recently_viewed_products', $recentlyViewedProducts->unique()->toArray());
    }

    public function render()
    {
        return view('livewire.guest.product-detail')->layout('layouts.guest');
    }
}
