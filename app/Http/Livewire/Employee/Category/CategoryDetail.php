<?php

namespace App\Http\Livewire\Employee\Category;


use App\Models\Category;
use Livewire\Component;

class CategoryDetail extends Component
{
    public Category $category;

    public bool $confirmingCategoryDeletion = false;

    public function delete()
    {
        $this->category->delete();

        $this->redirect(route('employee.categories.list'));
    }


    public function render()
    {
        return view('livewire.employee.category.category-detail')->layout('layouts.admin');
    }
}
