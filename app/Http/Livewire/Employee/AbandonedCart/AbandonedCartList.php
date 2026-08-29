<?php

namespace App\Http\Livewire\Employee\AbandonedCart;

use App\Models\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class AbandonedCartList extends Component
{
    use WithPagination;

    public $perPage = 15;
    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function markAsContacted($cartId)
    {
        $cart = Cart::find($cartId);

        if (! $cart) {
            return;
        }

        $cart->update(['contacted_at' => now()]);
        $this->notify(trans('Marcado como contatado.'));
    }

    public function getRowsProperty()
    {
        return Cart::query()
            ->whereNotNull('customer_id')
            ->whereNull('contacted_at')
            ->whereHas('items')
            ->where('updated_at', '<=', now()->subHours(2))
            ->with(['customer:id,name,email,phone,phone_country', 'items.product:id,name'])
            ->when($this->search, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->orderByDesc('updated_at')
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.employee.abandoned-cart.abandoned-cart-list', [
            'carts' => $this->rows,
        ])->layout('layouts.admin');
    }
}