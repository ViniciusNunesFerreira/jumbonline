<?php

namespace App\Http\Livewire\Employee\Product;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
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

    public function newProduct()
    {
        $product = Product::create([
            'name' => 'New product',
            'status' => 'DRAFT',
            'is_active' => false, 
        ]);

        // 2. Garante a criação da Variante Primária (resolvendo o erro de estoque nulo no PDV)
        $product->variants()->create([
            'stock_value' => 0,
            'weight_value' => 0,
            'weight_unit' => 'kg',
            'stock_tracking' => true,
        ]);

        $this->redirect(route('employee.products.detail', $product));
    }

    public function getRowsQueryProperty()
    {
        return Product::query()
            ->with('media', 'categories', 'variants', 'first_variant')
            ->when($this->search, fn($query, $search) => $query->where('name', 'like', '%' . $search . '%'))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.employee.product.product-list', [
            'products' => $this->rows,
        ])->layout('layouts.admin');
    }
}
