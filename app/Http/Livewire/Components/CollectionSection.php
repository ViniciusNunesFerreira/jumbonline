<?php

namespace App\Http\Livewire\Components;

use App\Models\Carousel;
use App\Models\Collection;
use Livewire\Component;

class CollectionSection extends Component
{
    public $handle;

    public $items;

    public function getCollectionItemsProperty()
    {
        if ($this->items) {
            return Collection::with('media')->whereIn('id', $this->items)->get();
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
        return view('livewire.components.collection-section');
    }
}
