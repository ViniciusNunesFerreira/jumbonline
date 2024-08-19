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
            return Product::with(['reviews', 'media'])->whereIn('id', $this->items)->active()->get();
        }
    }

    public function getCollectionCarouselProperty()
    {
        if (!$this->items && $this->handle) {
            return Carousel::with('slides.media')->where('slug', $this->handle)->first();
        }
    }

    public function render()
    {
        return view('livewire.components.product-section');
    }
}
