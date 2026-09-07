<?php

namespace App\Http\Livewire\Employee\AbandonedCart;

use App\Enums\InteractionChannel;
use App\Enums\InteractionType;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
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
        $cart = Cart::with('customer')->find($cartId);

        if (! $cart) {
            return;
        }

        $cart->update(['contacted_at' => now()]);

        if ($cart->customer) {
            $cart->customer->interactions()->create([
                'employee_id' => Auth::guard('employee')->id(),
                'channel' => InteractionChannel::WHATSAPP,
                'type' => InteractionType::CARRINHO_ABANDONADO,
                'description' => __('Contato de recuperação de carrinho abandonado registrado como realizado.'),
                'meta' => ['cart_id' => $cart->id],
            ]);
        }

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