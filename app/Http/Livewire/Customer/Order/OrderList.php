<?php

namespace App\Http\Livewire\Customer\Order;

use App\Models\Order;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public Review $review;

    public $productBeingReviewed;

    public $showReviewForm = false;

    protected $rules = [
        'review.rating' => 'required|integer|min:1|max:5',
        'review.title' => 'required|string|max:255',
        'review.content' => 'required|string',
    ];

    public function getRowsQueryProperty()
    {
        return Order::query()
            ->with([
                'orderDiscounts.orderItem',
                'orderItems:id,order_id,product_id,variant_id,price,quantity,subtotal',
                'orderItems.product:id,name,slug,excerpt,price',
                'orderItems.product.media',
                'orderItems.product.reviews' => function ($query) {
                    $query->select('reviews.product_id', 'reviews.rating')->where('customer_id', $this->customer->id)->latest();
                },
                'orderItems.variant:id,product_id,sku,price',
                'orderItems.variant.media',
                'orderItems.variant.variantAttributes.option',
                'orderItems.variant.variantAttributes.optionValue',
            ])
            ->where('customer_id', $this->customer->id)
            ->latest();
    }

    public function writeReviewForProduct($productId)
    {
        $this->review = Review::where('customer_id', $this->customer->id)->where('product_id', $productId)->firstOrNew();

        $this->productBeingReviewed = $productId;

        $this->showReviewForm = true;
    }

    public function saveReview()
    {
        $this->validate();

        $this->review->customer_id = $this->customer->id;

        $this->review->product_id = $this->productBeingReviewed;

        $this->review->save();

        $this->showReviewForm = false;
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate(10);
    }

    public function getCustomerProperty(): \App\Models\Customer
    {
        return \Auth::user();
    }

    public function render()
    {
        return view('livewire.customer.order.order-list', [
            'orders' => $this->rows,
        ])->layout('layouts.guest');
    }
}
