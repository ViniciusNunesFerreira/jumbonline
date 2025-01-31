<?php

namespace App\Http\Livewire\Guest;

use App\Models\Order;
use App\Models\Variant;
use Livewire\Component;

class OrderDetail extends Component
{
    public Order $order;

    public function mount()
    {
        $this->order->load([
              'orderItems:id,order_id,product_id,variant_id,price,quantity,subtotal',
            'orderItems.product:id,name,slug,excerpt,price',
            'orderItems.product.media',
            'orderItems.variant:id,product_id,sku,price,shipping_type',
            'orderItems.variant.media',
            'orderItems.variant.variantAttributes.option',
            'orderItems.variant.variantAttributes.optionValue',
            'orderItems.shipmentItems',
            'visitante',
            'prison_unit',
        ]);
    }

    public function downloadDigitalAttachment(Variant $variant)
    {
        $this->order->load([
        
            'orderItems:id,order_id,product_id,variant_id,price,quantity,subtotal',
            'orderItems.product:id,name,slug,excerpt,price',
            'orderItems.product.media',
            'orderItems.variant:id,product_id,sku,price,shipping_type',
            'orderItems.variant.media',
            'orderItems.variant.variantAttributes.option',
            'orderItems.variant.variantAttributes.optionValue',
            'orderItems.shipmentItems',
            'visitante',
            'prison_unit',
        ]);

        return $variant->getFirstMedia('attachment');
    }

    public function getBillingAddressProperty()
    {
        return $this->order->visitante;
    }

    public function getShippingAddressProperty()
    {
        return $this->order->prison_unit;
    }

    public function render()
    {
        return view('livewire.guest.order-detail')->layout('layouts.guest');
    }
}
