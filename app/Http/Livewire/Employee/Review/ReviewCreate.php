<?php

namespace App\Http\Livewire\Employee\Review;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Livewire\Component;

class ReviewCreate extends Component
{
    public $review;

    public $customers = [];

    public $orders = [];

    public $products = [];

    public $isReviewExists = false;

    protected $rules = [
        'review.customer_id' => 'required|exists:customers,id',
        'review.order_id' => 'required|exists:orders,id',
        'review.product_id' => 'required|exists:products,id',
        'review.title' => 'required|string|max:255',
        'review.content' => 'required|string',
        'review.rating' => 'required|numeric|min:1|max:5',
    ];

    public function mount()
    {
        $this->customers = Customer::query()->select('id', 'name')->get();

        $this->review = new Review();
    }

    public function updatedReviewCustomerId($value)
    {
        $this->review->order_id = '';

        $this->review->product_id = '';

        $this->orders = Order::query()->where('customer_id', $value)->select('id')->get();
    }

    public function updatedReviewOrderId($value)
    {
        $this->review->product_id = '';

        $selectedOrder = Order::query()
            ->with('orderItems:id,order_id,product_id')
            ->select('id')
            ->find($value);

        $this->products = Product::query()
            ->whereIn('id', $selectedOrder->orderItems->pluck('product_id'))
            ->select('id', 'name')
            ->get();
    }

    public function updatedReviewProductId($value)
    {
        $review = Review::query()
            ->where([
                'customer_id' => $this->review->customer_id,
                'order_id' => $this->review->order_id,
                'product_id' => $value,
            ])
            ->first();

        $this->review->id = $review ? $review->id : '';

        $this->review->title = $review ? $review->title : '';

        $this->review->content = $review ? $review->content : '';

        $this->review->rating = $review ? $review->rating : '';

        $this->isReviewExists = $review;
    }

    public function save()
    {
        $this->validate();

        $this->review->save();

        return redirect()->route('employee.reviews.detail', $this->review);
    }

    public function render()
    {
        return view('livewire.employee.review.review-create')->layout('layouts.admin');
    }
}
