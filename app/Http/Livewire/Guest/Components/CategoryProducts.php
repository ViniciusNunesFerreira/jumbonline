<?php

namespace App\Http\Livewire\Guest\Components;

use Livewire\Component;
use App\Models\Category;

class CategoryProducts extends Component
{
    public Category $category;
    public ?int $selectedProductId = null;
    public int $selectedQuantity = 0;
    public bool $showProducts = false;

    protected $listeners = [
        'categoryReset' => 'onCategoryReset',
        'categorySync'  => 'onCategorySync',
    ];

    public function mount(?int $selectedProductId = null, int $selectedQuantity = 0)
    {
        $this->selectedProductId = $selectedProductId;
        $this->selectedQuantity = $selectedQuantity;
    }

    // Escuta a remoção e reseta APENAS se o evento for referente a esta categoria
    public function onCategoryReset(int $categoryId)
    {
        if ($this->category->id === $categoryId) {
            $this->selectedProductId = null;
            $this->selectedQuantity = 0;
        }
    }

    public function onCategorySync(int $categoryId, int $productId, int $quantity)
    {
        if ($this->category->id === $categoryId) {
            $this->selectedProductId = $productId;
            $this->selectedQuantity = $quantity;
        }
    }

    public function selectCategory()
    {
        $this->showProducts = !$this->showProducts;
    }

    // Ao escolher, seleciona automaticamente a quantidade máxima permitida da categoria
    public function selectProduct(int $productId, int $variantId, float $weight)
    {
        $maxQuantity = $this->category->quantity;

        $this->selectedProductId = $productId;
        $this->selectedQuantity = $maxQuantity;

        $this->emitUp('addCart', [
            'product'  => $productId,
            'variant'  => $variantId,
            'category' => $this->category->id,
            'quantity' => $maxQuantity,
            'weight'   => $weight,
        ]);
    }

    public function incrementQuantity(int $variantId, float $weight)
    {
        if (!$this->selectedProductId) return;

        if ($this->selectedQuantity < $this->category->quantity) {
            $this->selectedQuantity++;

            $this->emitUp('addCart', [
                'product'  => $this->selectedProductId,
                'variant'  => $variantId,
                'category' => $this->category->id,
                'quantity' => $this->selectedQuantity,
                'weight'   => $weight,
            ]);
        }
    }

    public function decrementQuantity(int $variantId, float $weight)
    {
        if (!$this->selectedProductId) return;

        if ($this->selectedQuantity > 1) {
            $this->selectedQuantity--;

            $this->emitUp('addCart', [
                'product'  => $this->selectedProductId,
                'variant'  => $variantId,
                'category' => $this->category->id,
                'quantity' => $this->selectedQuantity,
                'weight'   => $weight,
            ]);
        } else {
            $this->removeSelection();
        }
    }

    public function removeSelection()
    {
        $this->selectedProductId = null;
        $this->selectedQuantity = 0;

        $this->emitUp('removeCategorySelection', $this->category->id);
    }

    public function render()
    {
        return view('livewire.guest.components.category-products');
    }
}