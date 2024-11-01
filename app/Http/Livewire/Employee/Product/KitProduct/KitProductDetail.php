<?php

namespace App\Http\Livewire\Employee\Product\KitProduct;

use Livewire\Component;
use App\Models\Kit;

class KitProductDetail extends Component
{
    public Kit $kit;

    public int $products_count = 0;

    protected $listeners = [
        'refresh' => '$refresh',
        'reload' => 'reload',
    ];

    public function mount()
    {
        $this->kit->load('products')->loadCount('products');
        $this->products_count = $this->kit->products_count;
        
    }

    public function render()
    {
        return view('livewire.employee.product.kit-product.kit-product-detail')->layout('layouts.admin');
    }
}
