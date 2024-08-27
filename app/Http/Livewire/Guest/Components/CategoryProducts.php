<?php

namespace App\Http\Livewire\Guest\Components;

use Livewire\Component;
use App\Models\Category;
use App\Models\Variant;
use App\Models\Product;

class CategoryProducts extends Component
{

    public Category $category;

    public Product $product;

    public  $quantity = 0;

    public int $maxQuantity;

    public $showProducts = false;

    public array $selectedOptionValues ;

    public $product_id = null;

    public array $add = [
        'product' => null,
        'variant' => null,
        'category' => null,
        'quantity' => 0,
        'weight' => 0
    ];

    public string $variantQuery = '';

    protected $queryString = ['variantQuery' => ['except' => '', 'as' => 'variant']];

    public function mount()
    {
        $this->maxQuantity = $this->category->quantity;
    }


    public function selectCategory()
    {
        $this->showProducts = !$this->showProducts;
        $this->showProducts ? ($this->quantity = $this->category->quantity) :  ($this->quantity = 0) ;

    }

    public function incrementQuantity()
    {
        if( $this->quantity < $this->category->quantity ){
             $this->quantity++;   
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



    public function addToCart($product)
    {
       if( $this->quantity > $this->maxQuantity) {
            abort(403);
       }

       $this->product = $this->category->products()->with('variants')->find($product);
       $variant = $this->product->variants()->first();
       
       $this->add['product'] =  $this->product->id;
       $this->add['variant'] =  $variant->id;
       $this->add['quantity'] = $this->quantity;
       $this->add['category'] = $this->category->id;
       $this->add['weight'] =   $variant->weight_unit == 'g' ? ($variant->weight_value / 1000) : $variant->weight_value;

       $this->emit('addCart', $this->add)->to('guest.product-list');

    }

    public function render()
    {
        return view('livewire.guest.components.category-products');
    }
}
