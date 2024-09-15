<?php

namespace App\Http\Livewire\Guest;

use Livewire\Component;
use App\Models\Order;
use App\Http\Livewire\Traits\MercadopagoPayment;

class OrderPayment extends Component
{
    use MercadopagoPayment;
    public Order $order;

    public function render()
    {
        return view('livewire.guest.order-payment')->layout('layouts.guest');
    }
}
