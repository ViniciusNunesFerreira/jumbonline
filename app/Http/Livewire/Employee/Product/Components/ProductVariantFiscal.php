<?php

namespace App\Http\Livewire\Employee\Product\Components;

use App\Models\Product;
use App\Models\Variant;
use Livewire\Component;

class ProductVariantFiscal extends Component
{
    public Product $product;

    public Variant $variant;

    protected function rules()
    {
        return [
            'variant.ncm' => 'nullable|digits:8',
            'variant.cfop' => 'nullable|digits:4',
            'variant.origin' => 'nullable|integer|between:0,8',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->variant->save();

        $this->notify(trans('Classificação fiscal salva.'));
    }

    public function render()
    {
        return view('livewire.employee.product.components.product-variant-fiscal');
    }
}