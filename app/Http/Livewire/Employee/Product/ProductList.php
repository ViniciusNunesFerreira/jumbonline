<?php

namespace App\Http\Livewire\Employee\Product;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $perPage = 10;

    public string $search = '';

    public bool $confirmingBulkDelete = false;

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

        $product->variants()->create([
            'stock_value' => 0,
            'weight_value' => 0,
            'weight_unit' => 'kg',
            'stock_tracking' => true,
        ]);

        $this->redirect(route('employee.products.detail', $product));
    }

    /**
     * Separa a seleção entre "pode excluir" (nunca apareceu num pedido) e
     * "bloqueado" (já foi vendido — não oferece a opção pra não gerar um
     * erro de integridade do banco).
     */
    public function getSelectedProductsInfoProperty()
    {
        if (empty($this->selected)) {
            return [collect(), collect()];
        }

        $products = Product::whereIn('id', $this->selected)->with('variants:id,product_id')->get();

        $variantIds = $products->flatMap->variants->pluck('id');

        $orderedProductIds = OrderItem::whereIn('product_id', $products->pluck('id'))->distinct()->pluck('product_id');
        $orderedVariantIds = $variantIds->isNotEmpty()
            ? OrderItem::whereIn('variant_id', $variantIds)->distinct()->pluck('variant_id')
            : collect();

        return $products->partition(function ($product) use ($orderedProductIds, $orderedVariantIds) {
            $hasOrderedVariant = $product->variants->pluck('id')->intersect($orderedVariantIds)->isNotEmpty();

            return $orderedProductIds->contains($product->id) || $hasOrderedVariant;
        });
    }

    public function confirmBulkDelete()
    {
        $this->confirmingBulkDelete = true;
    }

    public function bulkDelete()
    {
        [$blocked, $deletable] = $this->selectedProductsInfo;

        foreach ($deletable as $product) {
            $product->variants()->delete();
            $product->specifications()->delete();
            $product->categories()->detach();
            $product->clearMediaCollection('gallery');
            $product->clearMediaCollection('images');
            $product->delete();
        }

        $deletedCount = $deletable->count();
        $blockedCount = $blocked->count();

        $this->confirmingBulkDelete = false;
        $this->clearSelection();

        if ($blockedCount > 0) {
            $this->notify(trans(':deleted produto(s) excluído(s). :blocked não puderam ser excluídos por já terem pedidos vinculados.', ['deleted' => $deletedCount, 'blocked' => $blockedCount]));
        } else {
            $this->notify(trans(':count produto(s) excluído(s).', ['count' => $deletedCount]));
        }
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