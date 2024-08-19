<?php

namespace App\Http\Livewire\Employee\Category;

use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Category;

class CategoryList extends Component
{

    use WithBulkActions;
    use WithPagination;

    public $perPage = 10;

    public $showNewCategoryCreationModal = false;

    public $newCategory = [
        'title' => '',
        'description' => '',
    ];

    public $showDeleteConfirmationModal = false;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'newCategory.title' => 'required|string',
        'newCategory.description' => 'nullable|string',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->clearSelection();
    }

    public function deleteSelected()
    {
        Category::query()->whereIn('id', $this->selected)->delete();

        $this->reset('selectAll', 'selectPage', 'selected');

        $this->showDeleteConfirmationModal = false;
    }


    public function createNewCategory()
    {
        $this->reset('newCategory');

        $this->showNewCategoryCreationModal = true;
    }

    public function saveNewCategory()
    {
        $this->validate();

       $categoria = new Category();

       $categoria->title = $this->newCategory['title'];

       $categoria->description = $this->newCategory['description'];

       $categoria->save();

        $this->showNewCategoryCreationModal = false;

        $this->redirect(route('employee.categories.list',$categoria));
    }



    public function getRowsQueryProperty()
    {
        return Category::query()
            ->with('media')
            ->withCount('products')
            ->when($this->search, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }


    public function render()
    {
        return view('livewire.employee.category.category-list', [
            'categories' => $this->rows,
        ])->layout('layouts.admin');
    }
}
