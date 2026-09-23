<?php

namespace App\Http\Livewire\Employee\Product\Components;

use App\Models\Variant;
use Livewire\Component;

class LowStockAlert extends Component
{
    public function getVariantsProperty()
    {
        return Variant::query()
            ->where('stock_tracking', true)
            ->with('product:id,name')
            ->get()
            ->filter(fn($variant) => $variant->is_low_stock)
            ->sortBy('stock_value')
            ->take(10)
            ->values();
    }

    public function render()
    {
        return view('livewire.employee.product.components.low-stock-alert');
    }
}