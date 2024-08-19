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

    public int $quantity = 0;

    public $showProducts = false;
    public array $selectedOptionValues;

    public array $addToCart = [
        'product' => null,
        'variant' => null,
        'quantity' => 0,
    ];

    public string $variantQuery = '';

    protected $queryString = ['variantQuery' => ['except' => '', 'as' => 'variant']];


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

    public function addToCart()
    {
        $this->cart->items()->updateOrCreate([
            'product_id' => $this->addToCart['product'],
            'variant_id' => $this->addToCart['variant'],
        ], [
            'quantity' => $this->addToCart['quantity'],
        ]);

        $this->emit('refresh')->to('guest.components.header');

        $this->emit('show')->to('guest.components.cart-slide');
    }

    public function render()
    {
        return view('livewire.guest.components.category-products');
    }
}
