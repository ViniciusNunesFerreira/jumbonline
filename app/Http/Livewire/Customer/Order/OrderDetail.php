<?php

namespace App\Http\Livewire\Customer\Order;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Review;
use App\Models\Variant;
use Livewire\Component;
use Razorpay\Api\Api;
use Stripe\StripeClient;

class OrderDetail extends Component
{
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
    }

    public function writeReviewForProduct($productId)
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

        return $variant->getFirstMedia('attachment');
    }

    public function processStripePayment()
    {
        $stripe = new StripeClient(config('services.stripe.secret_key'));

        try {
            $session = $stripe->checkout->sessions->retrieve($this->order->meta['stripe_session_id'], []);

            return redirect($session->url);
        } catch (\Exception $e) {
            return logger($e->getMessage());
        }
    }

    public function verifyRazorpayPayment($razorpay_payment_id, $razorpay_signature)
    {
        $api = new Api(config('services.razorpay.api_key'), config('services.razorpay.api_secret'));

        $razorpay_order_id = $this->order->meta['razorpay_order_id'];

        $attributes = [
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_signature' => $razorpay_signature,
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);

            if ($this->order->payment_status == PaymentStatus::UNPAID) {
                $this->order->payment_status = PaymentStatus::PENDING;
            }

            $this->order->update([
                'meta' => [
                    'razorpay_order_id' => $razorpay_order_id,
                    'razorpay_payment_id' => $razorpay_payment_id,
                    'razorpay_signature' => $razorpay_signature,
                ],
            ]);
        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }

    public function getCustomerProperty()
    {
        return \Auth::user();
    }

    public function getBillingAddressProperty()
    {
        return $this->order->addresses->where('is_billing', true)->first();
    }

    public function getShippingAddressProperty()
    {
        return $this->order->addresses->where('is_billing', false)->first();
    }

    public function render()
    {
        return view('livewire.customer.order.order-detail')->layout('layouts.guest');
    }
}
