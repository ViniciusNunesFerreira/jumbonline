<?php

namespace App\Http\Livewire\Employee\PrisonUnid;

use Livewire\Component;
use App\Models\PrisonUnit;
use App\Http\Livewire\Traits\WithBulkActions;
use Livewire\WithPagination;

class PrisonList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
    ];


    public function getRowsQueryProperty()
    {
        return PrisonUnit::query()
            ->with(['prisonCategory'])
            ->when($this->search, fn($query, $search) => $query->where('name', 'like', '%' . $search . '%')->orWhere('logradouro', 'like', '%' . $search . '%'))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->clearSelection();
    }


    public function render()
    {
        return view('livewire.employee.prison-unid.prison-list',[
            'prison_units' => $this->rows,
        ])->layout('layouts.admin');
    }
}
