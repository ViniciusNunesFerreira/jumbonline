<?php

namespace App\Http\Livewire\Employee\Customer;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Customer;
use App\Models\PrisonUnit;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $search = '';

    public $filterPrisonUnit = '';

    public $filterStatus = '';

    public $filterLtvMin = '';

    public $filterLtvMax = '';

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterPrisonUnit' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterLtvMin' => ['except' => ''],
        'filterLtvMax' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterPrisonUnit()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterLtvMin()
    {
        $this->resetPage();
    }

    public function updatedFilterLtvMax()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->clearSelection();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterPrisonUnit', 'filterStatus', 'filterLtvMin', 'filterLtvMax']);
        $this->resetPage();
    }

    public function getHasActiveFiltersProperty()
    {
        return $this->filterPrisonUnit !== '' || $this->filterStatus !== '' || $this->filterLtvMin !== '' || $this->filterLtvMax !== '';
    }

    public function getPrisonUnitsProperty()
    {
        return PrisonUnit::orderBy('name')->get(['id', 'name']);
    }

    public function getRowsQueryProperty()
    {
        return Customer::query()
            ->with('media')
            ->when($this->search, fn($query, $search) => $query
                ->where(fn($q) => $q
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')))
            ->when($this->filterPrisonUnit !== '', fn($query) => $query
                ->where(fn($q) => $q
                    ->whereHas('detentos', fn($dq) => $dq->where('prison_unit_id', $this->filterPrisonUnit))
                    ->orWhereHas('visitantes', fn($vq) => $vq->where('prison_unit_id', $this->filterPrisonUnit))))
            ->when($this->filterStatus === 'active', fn($query) => $query->whereNull('banned_at'))
            ->when($this->filterStatus === 'banned', fn($query) => $query->whereNotNull('banned_at'))
            ->when($this->filterLtvMin !== '', fn($query) => $query->where('ltv_total', '>=', $this->filterLtvMin))
            ->when($this->filterLtvMax !== '', fn($query) => $query->where('ltv_total', '<=', $this->filterLtvMax))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.employee.customer.customer-list', [
            'customers' => $this->rows,
            'prisonUnits' => $this->prisonUnits,
        ])->layout('layouts.admin');
    }
}