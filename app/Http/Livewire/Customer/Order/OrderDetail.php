<?php

namespace App\Http\Livewire\Customer\Order;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Review;
use App\Models\Variant;
use Livewire\Component;
use App\Http\Livewire\Traits\MercadopagoPayment;

class OrderDetail extends Component
{

    use MercadopagoPayment;
    
    public Order $order;

    public Review $review;

    public $productBeingReviewed;

    public $showReviewForm = false;

    protected $rules = [
        'review.rating' => 'required|integer|min:1|max:5',
        'review.title' => 'required|string|max:255',
        'review.content' => 'required|string',
    ];

    public function mount()
    {
        $this->order->load([
            'orderItems:id,order_id,product_id,variant_id,price,quantity,subtotal',
            'orderItems.product:id,name,slug,excerpt,price',
            'orderItems.product.media',
            'orderItems.product.reviews' => function ($query) {
                $query->select('reviews.product_id', 'reviews.rating')->where('customer_id', $this->customer->id)->latest();
            },
            'orderItems.variant:id,product_id,sku,price,shipping_type',
            'orderItems.variant.media',
            'orderItems.variant.variantAttributes.option',
            'orderItems.variant.variantAttributes.optionValue',
            'orderItems.shipmentItems',
            'prison_unit',
            'visitante'
        ]);
    }

    public function writeReviewForProduct($productId)
    {
        $this->order->load([
            'orderItems:id,order_id,product_id,variant_id,price,quantity,subtotal',
            'orderItems.product:id,name,slug,excerpt,price',
            'orderItems.product.media',
            'orderItems.product.reviews' => function ($query) {
                $query->select('reviews.product_id', 'reviews.rating')->where('customer_id', $this->customer->id)->latest();
            },
            'orderItems.variant:id,product_id,sku,price,shipping_type',
            'orderItems.variant.media',
            'orderItems.variant.variantAttributes.option',
            'orderItems.variant.variantAttributes.optionValue',
            'orderItems.shipmentItems',
            'prison_unit',
            'visitante'
        ]);

        $this->review = Review::where('customer_id', $this->customer->id)->where('product_id', $productId)->firstOrNew();

        $this->productBeingReviewed = $productId;

        $this->showReviewForm = true;
    }

    public function saveReview()
    {
        $this->order->load([
            'addresses.country:id,name',
            'orderItems:id,order_id,product_id,variant_id,price,quantity,subtotal',
            'orderItems.product:id,name,slug,excerpt,price',
            'orderItems.product.media',
            'orderItems.product.reviews' => function ($query) {
                $query->select('reviews.product_id', 'reviews.rating')->where('customer_id', $this->customer->id)->latest();
            },
            'orderItems.variant:id,product_id,sku,price,shipping_type',
            'orderItems.variant.media',
            'orderItems.variant.variantAttributes.option',
            'orderItems.variant.variantAttributes.optionValue',
            'orderItems.shipmentItems',
        ]);

        $this->validate();

        $this->review->customer_id = $this->customer->id;

        $this->review->product_id = $this->productBeingReviewed;

        $this->review->save();

        $this->showReviewForm = false;
    }

    public function downloadDigitalAttachment(Variant $variant)
    {
        $this->order->load([
            
            'orderItems:id,order_id,product_id,variant_id,price,quantity,subtotal',
            'orderItems.product:id,name,slug,excerpt,price',
            'orderItems.product.media',
            'orderItems.product.reviews' => function ($query) {
                $query->select('reviews.product_id', 'reviews.rating')->where('customer_id', $this->customer->id)->latest();
            },
            'orderItems.variant:id,product_id,sku,price,shipping_type',
            'orderItems.variant.media',
            'orderItems.variant.variantAttributes.option',
            'orderItems.variant.variantAttributes.optionValue',
            'orderItems.shipmentItems',
            'visitante',
            'prison_unit'
        ]);

        return $variant->getFirstMedia('attachment');
    }

    public function getCustomerProperty()
    {
        return \Auth::user();
    }

    public function getBillingAddressProperty()
    {
        return (object) ($this->order->visitante_snapshot ?? (array) $this->order->visitante);
    }

    public function getDetentoSnapshotProperty()
    {
        return (object) ($this->order->detento_snapshot ?? (array) optional($this->order->detento));
    }

    public function getShippingAddressProperty()
    {
        
        return $this->order->prison_unit;
    }

    public function render()
    {
        return view('livewire.customer.order.order-detail')->layout('layouts.guest');
    }
}
