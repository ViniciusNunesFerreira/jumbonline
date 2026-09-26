<?php

namespace App\Http\Livewire\Employee\Discount;

use App\Enums\ProductType;
use App\Models\Collection;
use App\Models\Discount;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DiscountDetail extends Component
{
    public Discount $discount;

    /**
     * 'automatic' = sem código, aplica sozinho dentro da validade.
     * 'code' = cliente digita um código no carrinho.
     */
    public string $activationType = 'code';

    public $collections = [];

    public $showCollectionModal = false;

    public $selectedCollections = [];

    public $filterCollectionTitle = '';

    public $products = [];

    public $showProductModal = false;

    public $selectedProducts = [];

    public $filterProductName = '';

    public $startDate = null;

    public $startTime = null;

    public $endDate = null;

    public $endTime = null;

    public $hasEnd = false;

    protected function rules()
    {
        return [
            'discount.code' => [
                'nullable',
                'string',
                Rule::unique('discounts', 'code')->ignore($this->discount->id),
            ],
            'discount.type' => 'required|in:fixed,percentage',
            'discount.value' => 'required|numeric|min:0',
            'discount.usage_limit' => 'nullable|integer|min:1',
            'discount.applies_to' => 'required|in:collections,products,orders',
            'startDate' => 'date',
            'startTime' => 'date',
            'endDate' => 'nullable|date',
            'endTime' => 'nullable|date',
            'selectedCollections' => 'required_if:discount.applies_to,collections|array',
            'selectedProducts' => 'required_if:discount.applies_to,products|array',
        ];
    }

    protected $messages = [
        'discount.code.unique' => 'Já existe um desconto com este código.',
        'selectedCollections.required_if' => 'Selecione pelo menos um grupo.',
        'selectedProducts.required_if' => 'Selecione pelo menos um kit.',
    ];

    public function mount()
    {
        if (Route::currentRouteName() === 'employee.discounts.create') {
            $this->discount = new Discount([
                'type' => 'percentage',
                'applies_to' => 'orders',
            ]);

            $this->startDate = now()->toISOString();

            $this->startTime = now()->toISOString();

            $this->endDate = now()->toISOString();

            $this->endTime = now()->toISOString();
        } else {
            $this->discount->load([
                'collections' => function ($query) {
                    $query->select('discount_id', 'collection_id')->withPivot('collection_id');
                },
                'products' => function ($query) {
                    $query->select('discount_id', 'product_id')->withPivot('product_id');
                },
            ]);

            $this->activationType = $this->discount->code ? 'code' : 'automatic';

            $this->startDate = $this->discount->starts_at->toISOString();

            $this->startTime = $this->discount->starts_at->toISOString();

            $this->hasEnd = (bool) $this->discount->ends_at;

            $this->endDate = $this->hasEnd ? $this->discount->ends_at->toISOString() : now()->toISOString();

            $this->endTime = $this->hasEnd ? $this->discount->ends_at->toISOString() : now()->toISOString();

            $this->selectedCollections = $this->discount->collections->pluck('collection_id')->toArray();

            $this->selectedProducts = $this->discount->products->pluck('product_id')->toArray();
        }
    }

    public function searchCollections(?string $title = '')
    {
        if (!empty($title)) $this->filterCollectionTitle = $title;

        $this->showCollectionModal = true;
    }

    public function updatedfilterCollectionTitle()
    {
        $this->loadCollections();
    }

    public function loadCollections(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->collections = Collection::query()
            ->with('media')
            ->when($this->filterCollectionTitle, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->get();
    }

    public function addCollections()
    {
        $this->selectedCollections = array_values(array_unique($this->selectedCollections));

        $this->showCollectionModal = false;
    }

    public function removeCollections($collectionId)
    {
        $this->selectedCollections = array_values(array_diff($this->selectedCollections, [$collectionId]));
    }

    public function searchProducts(?string $name = '')
    {
        if (!empty($name)) $this->filterProductName = $name;

        $this->showProductModal = true;
    }

    public function updatedfilterProductName()
    {
        $this->loadProducts();
    }

    /**
     * Só produtos do tipo Kit — Simples nunca é vendido avulso, então nunca
     * faz sentido oferecer desconto nele individualmente.
     */
    public function loadProducts()
    {
        $this->products = Product::query()
            ->with('media')
            ->where('type', ProductType::KIT->name)
            ->when($this->filterProductName, fn($query, $search) => $query->where('name', 'like', '%' . $search . '%'))
            ->get();
    }

    public function addProducts()
    {
        $this->selectedProducts = array_values(array_unique($this->selectedProducts));

        $this->showProductModal = false;
    }

    public function removeProducts($productId)
    {
        $this->selectedProducts = array_values(array_diff($this->selectedProducts, [$productId]));
    }

    public function getCurrentCollectionsProperty(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Collection::query()
            ->with('media')
            ->whereIn('id', $this->selectedCollections)
            ->get();
    }

    public function getCurrentProductsProperty(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Product::query()
            ->with('media')
            ->whereIn('id', $this->selectedProducts)
            ->get();
    }

    public function save()
    {
        if ($this->activationType === 'automatic') {
            $this->discount->code = null;
        }

        $this->validate();

        if ($this->activationType === 'code' && empty($this->discount->code)) {
            $this->addError('discount.code', 'Informe um código, ou mude a ativação para automática.');
            return;
        }

        $this->discount->starts_at = Carbon::parse($this->startDate)->setTimeFrom(Carbon::parse($this->startTime))->toDateTimeString();

        $this->discount->ends_at = $this->hasEnd ? Carbon::parse($this->endDate)->setTimeFrom(Carbon::parse($this->endTime))->toDateTimeString() : null;

        $this->discount->save();

        if ($this->discount->applies_to === 'collections') {
            $this->discount->products()->detach();

            $this->discount->collections()->sync($this->selectedCollections);
        } elseif ($this->discount->applies_to === 'products') {
            $this->discount->collections()->detach();

            $this->discount->products()->sync($this->selectedProducts);
        } else {
            $this->discount->collections()->detach();

            $this->discount->products()->detach();
        }

        if ($this->discount->wasRecentlyCreated) {
            session()->flash('success', 'Desconto salvo com sucesso!');

            $this->redirect(route('employee.discounts.detail', $this->discount));
        }

        $this->notify('Desconto salvo com sucesso!');
    }

    public function render()
    {
        return view('livewire.employee.discount.discount-detail')->layout('layouts.admin');
    }
}