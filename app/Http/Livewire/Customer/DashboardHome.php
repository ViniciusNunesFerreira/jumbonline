<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;

class DashboardHome extends Component
{
    public function getCustomerProperty()
    {
        return \Auth::user();
    }

    public function getLastOrderProperty()
    {
        return Order::where('customer_id', $this->customer->id)
            ->with(['orderItems.product.media', 'orderItems.variant.media'])
            ->latest()
            ->first();
    }

    public function getOrdersCountProperty()
    {
        return Order::where('customer_id', $this->customer->id)->count();
    }

    public function render()
    {
        return view('livewire.customer.dashboard-home')->layout('layouts.guest');
    }
}