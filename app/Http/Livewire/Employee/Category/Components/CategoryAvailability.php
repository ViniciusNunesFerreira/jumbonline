<?php

namespace App\Http\Livewire\Employee\Category\Components;

use Livewire\Component;
use App\Models\Category;

class CategoryAvailability extends Component
{
    public Category $category;

    public $published_at;

    public function mount()
    {
        $this->published_at = $this->category->published_at ? $this->category->published_at->toDateTimeString() : null;
    }

    public function save()
    {
        $this->category->published_at = $this->published_at;

        $this->category->save();

        $this->notify(trans('Status de Categoria Atualizada'));

        $this->dispatchBrowserEvent('category-availability-updated');
    }

    public function render()
    {
        return view('livewire.employee.category.components.category-availability');
    }
}
