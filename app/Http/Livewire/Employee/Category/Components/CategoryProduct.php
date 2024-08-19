<?php

namespace App\Http\Livewire\Employee\Category\Components;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;


class CategoryProduct extends Component
{
    public Category $category;

    public $products = [];

    public $selected = [];

    public $search = '';

    public bool $isBrowsingProducts = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->category->load([
            'products' => function ($query) {
                $query->select('id', 'name', 'slug');
            },
            'products.media',
        ]);
    }


    public function browse()
    {
        $this->reset('products', 'selected', 'search');

        $this->selected = $this->category->products->pluck('id')->toArray();

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
        $this->category->products()->sync($this->selected);

        $this->emit('refresh')->self();

        $this->notify(trans('Product updated'));

        $this->isBrowsingProducts = false;
    }

    public function delete(Product $product)
    {
        $this->category->products()->detach($product);

        $this->notify(trans('Product removed from collection'));

        $this->emit('refresh')->self();
    }

    public function render()
    {
        return view('livewire.employee.category.components.category-product');
    }
}
