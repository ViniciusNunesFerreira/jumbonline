<?php

namespace App\Http\Livewire\Employee\Order\Components;

use App\Models\Order;
use App\Services\WhatsAppLinkService;
use Livewire\Component;

class OrderCustomerDetail extends Component
{
    public Order $order;

    public function mount()
    {
        $this->order->load([
            'customer:id,name,email,phone,phone_country',
            'billingAddress.country:id,name',
            'shippingAddress.country:id,name',
        ]);
    }

    public function getWhatsappUrlProperty(): ?string
    {
        return app(WhatsAppLinkService::class)->forOrder($this->order);
    }

    public function render()
    {
        return view('livewire.employee.order.components.order-customer-detail');
    }
}