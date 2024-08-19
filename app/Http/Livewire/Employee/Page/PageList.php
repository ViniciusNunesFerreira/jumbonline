<?php

namespace App\Http\Livewire\Employee\Page;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class PageList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public Page $newPage;

    public $addingNewPage = false;

    public $showDeleteConfirmationModal = false;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'newPage.title' => 'required',
    ];

    public function addNewPage()
    {
        $this->newPage = new Page();

        $this->addingNewPage = true;
    }

    public function saveNewPage()
    {
        $this->validate([
            'newPage.title' => 'required',
        ]);

        $this->newPage->save();

        $this->addingNewPage = false;

        $this->redirect(route('employee.pages.detail', $this->newPage));
    }

    public function deleteSelected()
    {
        Page::query()->whereIn('id', $this->selected)->delete();

        $this->reset('selectAll', 'selectPage', 'selected');

        $this->showDeleteConfirmationModal = false;
    }

    public function getRowsQueryProperty()
    {
        return Page::query()
            ->when($this->search, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate(10);
    }

    public function render()
    {
        return view('livewire.employee.page.page-list', [
            'pages' => $this->rows,
        ])->layout('layouts.admin');
    }
}
