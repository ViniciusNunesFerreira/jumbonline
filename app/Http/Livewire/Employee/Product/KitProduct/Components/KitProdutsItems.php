<?php

namespace App\Http\Livewire\Employee\Product\KitProduct\Components;

use Livewire\Component;
use App\Models\Product;
use App\Models\Kit;

class KitProdutsItems extends Component
{

    public Kit $kit;

    public $products = [];

    public $selected = [];

    public $product_list = [];

    public $search = '';

    public bool $isBrowsingProducts = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->kit->load([
            'products' => function ($query) {
                $query->select('name', 'slug');
            },
            'products.media',
        ]);
    }

    public function browse()
    {
        $this->reset('products', 'selected', 'search');

        $this->selected = $this->kit->products->pluck('id')->toArray();

        
        $this->isBrowsingProducts = true;
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

   
    public function loadProducts()
    {
        $this->products = Product::query()
            ->select('id', 'name')
            ->with('media')
            ->when($this->search, fn($query, $search) => $query->where('name', 'like', '%' . $search . '%'))
            ->latest()
            ->get();
    }

    public function save()
    {
       
        $this->kit->products()->sync($this->selected);

        $prods = [];

        foreach($this->selected as $sel){
            if( !empty($this->product_list[$sel]) ){
                array_push($prods, ['product_id' => intval($sel), 'quantity' => intval( $this->product_list[$sel]) ]);
            }
            
        }

        $this->kit->product_list = $prods;
        $this->kit->save();


        $this->emit('refresh')->self();

        $this->notify(trans('Product updated'));

        $this->isBrowsingProducts = false;
    }

    public function delete(Product $product)
    {
        $this->kit->products()->detach($product);

        if(!empty($this->kit->product_list) ){

            foreach($this->kit->product_list as $key => $prod){

                if($prod['product_id'] == $product->id){
                    $list = $this->kit->product_list;
                    unset($list[$key]);
                    $this->kit->product_list = $list;
                    $this->kit->save();
                }
            }

        }

        $this->notify(trans('Product removed from collection'));

        $this->emit('refresh')->self();
    }


    public function render()
    {
        return view('livewire.employee.product.kit-product.components.kit-produts-items');
    }
}
