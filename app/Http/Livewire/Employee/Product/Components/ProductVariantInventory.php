<?php

namespace App\Http\Livewire\Employee\Product\Components;

use App\Models\Product;
use App\Models\Variant;
use App\Services\StockMovementService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ProductVariantInventory extends Component
{
    public Product $product;

    public Variant $variant;

    public bool $showAdjustForm = false;

    public $newStockValue = 0;

    public $adjustReason = '';

    protected function rules()
    {
        return [
            'variant.sku' => 'nullable|string|unique:variants,sku,' . $this->variant->id,
            'variant.barcode' => 'nullable|string|unique:variants,barcode,' . $this->variant->id,
            'variant.low_stock_threshold' => 'nullable|integer|min:0',
            'variant.weight_value' => 'required|numeric|min:0',
            'variant.weight_unit' => ['required', Rule::in(['lb', 'oz', 'kg', 'g'])],
        ];
    }

    protected function adjustRules()
    {
        return [
            'newStockValue' => 'required|integer|min:0',
            'adjustReason' => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'adjustReason.required' => 'Descreva o motivo do ajuste (contagem, avaria, recebimento de fornecedor...).',
    ];

    public function updatedVariantSKU($value)
    {
        if (!$value) $this->variant->sku = null;
    }

    public function updatedVariantBarcode($value)
    {
        if (!$value) $this->variant->barcode = null;
    }

    public function openAdjustForm()
    {
        $this->newStockValue = $this->variant->stock_value;
        $this->adjustReason = '';
        $this->resetErrorBag();
        $this->showAdjustForm = true;
    }

    public function adjustStock(StockMovementService $stockMovementService)
    {
        $this->validate($this->adjustRules());

        $stockMovementService->registerAdjustment(
            $this->variant,
            (int) $this->newStockValue,
            Auth::guard('employee')->user(),
            $this->adjustReason
        );

        $this->variant->refresh();

        $this->showAdjustForm = false;

        $this->notify(trans('Estoque ajustado.'));
    }

    /**
     * Salva apenas os campos que não são a quantidade de estoque em si —
     * SKU, código de barras, peso e o limite de estoque baixo continuam
     * editáveis livremente. A quantidade de estoque só muda via
     * adjustStock(), que sempre passa pelo StockMovementService e exige
     * motivo.
     */
    public function save()
    {
        $this->validate();

        $this->variant->weight_value = $this->variant->weight_value;
        $this->variant->weight_unit = $this->variant->weight_unit;
        $this->variant->sku = $this->variant->sku;
        $this->variant->barcode = $this->variant->barcode;
        $this->variant->low_stock_threshold = $this->variant->low_stock_threshold;

        $this->variant->save();

        $this->dispatchBrowserEvent('variant-inventory-updated');

        $this->notify(trans('Inventory updated.'));
    }

    public function getRecentMovementsProperty()
    {
        return $this->variant->stockMovements()->with('employee')->latest()->limit(10)->get();
    }

    public function render()
    {
        return view('livewire.employee.product.components.product-variant-inventory', [
            'recentMovements' => $this->recentMovements,
        ]);
    }
}