<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use App\Http\Livewire\Traits\MercadopagoPayment;

class OrderPayment extends Component
{
    use MercadopagoPayment;

    public Order $order;

    public function render()
    {
        return view('livewire.customer.order-payment')->layout('layouts.guest');
    }
}
