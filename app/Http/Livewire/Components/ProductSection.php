<?php

namespace App\Http\Livewire\Components;

use App\Models\Carousel;
use App\Models\Product;
use Livewire\Component;

class ProductSection extends Component
{
    public $handle;

    public $items;

    public function getProductItemsProperty()
    {
        if ($this->items) {
            return $this->items;
        }
    }


    public function render()
    {
        return view('livewire.components.product-section');
    }
}
