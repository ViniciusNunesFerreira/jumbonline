<?php

namespace App\Http\Livewire\Employee\Product\Components;

use App\Models\Collection;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductOrganization extends Component
{
    public Product $product;

    public $selectedCollections = [];

    public function mount()
    {
        $this->selectedCollections = $this->product->categories->pluck('id')->toArray();
    }

    public function save()
    {
        $this->selectedCollections = array_map('intval', $this->selectedCollections);

        $this->product->categories()->sync($this->selectedCollections);

        $this->dispatchBrowserEvent('saved');

        $this->notify(trans('Grupo de produtos atualizado.'));
    }

    public function getCategoriesProperty()
    {
        return Category::query()->select('id', 'title')->get();
    }

    public function render()
    {
        return view('livewire.employee.product.components.product-organization');
    }
}
