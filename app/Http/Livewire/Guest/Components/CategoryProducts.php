<?php

namespace App\Http\Livewire\Guest\Components;

use Livewire\Component;
use App\Models\Category;
use App\Models\Variant;
use App\Models\Product;

use App\Models\Cart;

class CategoryProducts extends Component
{

    public Category $category;

    public Product $product;

    public  $quantity = 0;

    public int $maxQuantity;

    public $showProducts = false;

    public array $selectedOptionValues;

    protected $listeners = ['refreshCart'];

    public array $addToCart = [
        'product' => null,
        'variant' => null,
        'catetory' => null,
        'quantity' => 0,
    ];

    public string $variantQuery = '';

    protected $queryString = ['variantQuery' => ['except' => '', 'as' => 'variant']];

    public function mount()
    {
        $this->maxQuantity = $this->category->quantity;
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->selectedOptionValues = $this->cart->items()->pluck('product_id')->toArray();

    }

   
    public function selectCategory()
    {
        $this->showProducts = !$this->showProducts;
        $this->showProducts ? ($this->quantity = $this->category->quantity) :  ($this->quantity = 0) ;

    }

    public function incrementQuantity()
    {
        if( $this->quantity < $this->category->quantity ){
            return $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if($this->quantity > 0){

            if($this->quantity == 1){
                $this->showProducts = !$this->showProducts; 


            }

            return $this->quantity--;
        }
        
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

    public function addToCart($product)
    {
       if( $this->quantity > $this->maxQuantity) {
            abort(403);
       }

       $this->addCart($product);

    }

    protected function addCart($id)
    {
        $this->product = $this->category->products()->with('variants')->find($id);


        $this->cart->items()->updateOrCreate([
            'category_id' => $this->category->id,
        ], [
            'product_id' => $this->product->id,
            'variant_id' => $this->product->variants()->first()->id,
            'quantity' => $this->quantity,
        ]);

        $this->emit('refresh')->to('guest.components.header');

        $this->emit('show')->to('guest.components.cart-slide');

        $this->emitSelf('refreshCart');
        
    }

    public function render()
    {
        return view('livewire.guest.components.category-products');
    }
}
