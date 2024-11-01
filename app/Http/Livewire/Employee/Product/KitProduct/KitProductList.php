<?php

namespace App\Http\Livewire\Employee\Product\KitProduct;

use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Kit;

class KitProductList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $perPage = 10;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->clearSelection();
    }

    public function getRowsQueryProperty()
    {
        return Kit::query()
            ->with('media', 'products')
            ->when($this->search, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function newKit()
    {
        $kit = new Kit();
        $kit->title = 'Novo Kit';
        $kit->save();

        $this->redirect(route('employee.kits.detail', $kit));
    }

    public function render()
    {
        return view('livewire.employee.product.kit-product.kit-product-list',[
            'kits' => $this->rows,
        ])->layout('layouts.admin');
    }
}
