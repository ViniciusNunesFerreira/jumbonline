<?php

namespace App\Http\Livewire\Employee\Collection\Components;

use App\Models\Collection;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;

class CollectionProduct extends Component
{
    public Collection $collection;

    public $categories = [];

    public $selected = [];

    public $search = '';

    public bool $isBrowsingProducts = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->collection->load([
            'categories' => function ($query) {
                $query->select('id', 'title', 'slug');
            },
            'categories.media',
        ]);
    }

    public function browse()
    {
        $this->reset('categories', 'selected', 'search');

        $this->selected = $this->collection->categories->pluck('id')->toArray();

        $this->isBrowsingProducts = true;
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->categories = Category::query()
            ->select('id', 'title')
            ->with('media')
            ->when($this->search, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->latest()
            ->get();
    }

    public function save()
    {
        $this->collection->categories()->sync($this->selected);

        $this->emit('refresh')->self();

        $this->notify(trans('Categoria Atualizada'));

        $this->isBrowsingProducts = false;
    }

    public function delete(Category $category)
    {
        $this->collection->categories()->detach($category);

        $this->notify(trans('Categoria removida do Grupo'));

        $this->emit('refresh')->self();
    }

    public function render()
    {
        return view('livewire.employee.collection.components.collection-product');
    }
}
