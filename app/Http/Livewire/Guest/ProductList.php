<?php

namespace App\Http\Livewire\Guest;

use Livewire\Component;

use App\Models\PrisonUnit;
use Artesaos\SEOTools\Traits\SEOTools;
use App\Models\Cart;
use App\Models\Product;


class ProductList extends Component
{
    use SEOTools;

    public PrisonUnit $prison;
    public Product $product;

    public $perPage = 10;

    protected $listeners = ['refreshCart', 'addCart', 'updateCartItemQuantity'];

    public array $selectedOptions = [];

    public $subTotal = 0;
    public $weight_max = 12;
    public $weight = 0;

    public function mount()
    {
        $this->selectedOptions = $this->cart->items()->pluck('product_id')->toArray();
        $this->weight = $this->cart->weight;
        $this->subTotal = $this->cart->subTotal;
    }

    public function refreshCart()
    {
        $this->selectedOptions = $this->cart->items()->pluck('product_id')->toArray();
        $this->cart->load('items');
        $this->weight = $this->cart->weight;
        $this->subTotal = $this->cart->subTotal;

        $this->emit('refresh')->to('guest.components.header');
        $this->emit('show')->to('guest.components.cart-slide');
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

    public function addCart(Array $items)
    {
        if( ($this->cart->weight+$items['weight']) <= $this->weight_max){

            $this->cart->items()->updateOrCreate([
                'category_id' => $items['category'],
            ], [
                'product_id' => $items['product'],
                'variant_id' => $items['variant'],
                'quantity' => $items['quantity'],
            ]);

            $this->cart->load('items');
            $this->refreshCart();

        }else{
            $this->notify(trans('Peso máximo do Jumbo: '.$this->weight_max.' kg!'));
        }

        
    }


    public function updateCartItemQuantity($categoryId, $quantity)
    {
        
        $item =  $this->cartItems->where('category_id', $categoryId)->firstOrFail();
        $item->load('category');
       
        $max_quantity = $item->category->quantity;

        if ($quantity < 1) {
            $this->cartItems->find($item->id)->delete();
        }

        if($max_quantity >= $quantity){
            $this->cartItems->find($item->id)->update(['quantity' => $quantity]);
        }else{
            return  $this->notify('A quantidade máxima é: '.$max_quantity);
        }
        
        $this->emit('refresh')->self();

       
    }

    public function getCartItemsProperty()
    {
        return $this->cart->items;
    }


    public function getRowsQueryProperty()
    {
        return $this->prison->collections()->with('categoriesPublished.products')->get();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    

    public function render()
    {
        return view('livewire.guest.product-list', ['collections' => $this->rows, 'selectedOptions' => $this->selectedOptions ])->layout('layouts.guest');
    }
}
