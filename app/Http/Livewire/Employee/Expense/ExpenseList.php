<?php

namespace App\Http\Livewire\Employee\Expense;

use App\Enums\ExpenseStatus;
use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $perPage = 10;

    public $search = '';

    public $filterStatus = '';

    public $filterCategory = '';

    public $filterDueFrom = '';

    public $filterDueTo = '';

    public $showDeleteConfirmationModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterDueFrom' => ['except' => ''],
        'filterDueTo' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterDueFrom()
    {
        $this->resetPage();
    }

    public function updatedFilterDueTo()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->clearSelection();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterCategory', 'filterDueFrom', 'filterDueTo']);
        $this->resetPage();
    }

    public function getHasActiveFiltersProperty()
    {
        return $this->filterStatus !== '' || $this->filterCategory !== '' || $this->filterDueFrom !== '' || $this->filterDueTo !== '';
    }

    public function getCategoriesProperty()
    {
        return ExpenseCategory::orderBy('name')->get(['id', 'name']);
    }

    public function deleteSelected()
    {
        Expense::query()->whereIn('id', $this->selected)->delete();

        $this->clearSelection();

        $this->showDeleteConfirmationModal = false;

        $this->notify(trans('Conta(s) excluída(s).'));
    }

    public function getRowsQueryProperty()
    {
        return Expense::query()
            ->with(['payee:id,name', 'category:id,name'])
            ->when($this->search, fn ($query, $search) => $query
                ->where(fn ($q) => $q
                    ->where('description', 'like', '%' . $search . '%')
                    ->orWhereHas('payee', fn ($pq) => $pq->where('name', 'like', '%' . $search . '%'))))
            ->when($this->filterCategory !== '', fn ($query) => $query->where('expense_category_id', $this->filterCategory))
            ->when($this->filterDueFrom !== '', fn ($query) => $query->whereDate('due_date', '>=', $this->filterDueFrom))
            ->when($this->filterDueTo !== '', fn ($query) => $query->whereDate('due_date', '<=', $this->filterDueTo))
            ->when($this->filterStatus === ExpenseStatus::PAID->value, fn ($query) => $query->whereNotNull('payment_date'))
            ->when($this->filterStatus === ExpenseStatus::PENDING->value, fn ($query) => $query->whereNull('payment_date')->whereDate('due_date', '>=', today()))
            ->when($this->filterStatus === ExpenseStatus::OVERDUE->value, fn ($query) => $query->whereNull('payment_date')->whereDate('due_date', '<', today()))
            ->orderBy('due_date');
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.employee.expense.expense-list', [
            'expenses' => $this->rows,
            'categories' => $this->categories,
        ])->layout('layouts.admin');
    }
}