<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'customer_id',
        'customer_email',
        'payment_method',
        'notes',
        'meta',
        'contacted_at'
    ];

    protected $casts = [
        'meta' => AsArrayObject::class,
        'subtotal' => 'float',
        'total' => 'float',
        'isDigitalOnly' => 'boolean',
    ];

    protected $with = ['items.variant'];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function discounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartDiscount::class);
    }

    public function addresses(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function billingAddress(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('is_billing', true);
    }

    public function shippingAddress(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('is_billing', false);
    }

    protected function isDigitalOnly(): Attribute
    {
        return new Attribute(
            get: fn() => $this->items->where('product.is_physical', false)->count() >= 1
        );
    }

    protected function weight(): Attribute
    {
        return new Attribute(
            get: fn() => $this->items->reduce(function ($value, $item) {
                
                if (!$item->variant) {
                    return $value;
                }

                $itemWeight = \App\Models\Variant::convertWeightToKg($item->variant->weight_value, $item->variant->weight_unit);

                //Multiplica o peso individual pela quantidade do item!
                return $value + ($itemWeight * $item->quantity);
            }, 0)
        );
    }

    protected function subtotal(): Attribute
    {
        return new Attribute(
            get: fn() => $this->items->reduce(function ($value, $item) {
                return $value + $item->subtotal;
            }, 0)
        );
    }

    protected function total(): Attribute
    {
        return new Attribute(
            get: fn() => $this->subtotal + $this->shippingMethod?->price ?? 0
        );
    }

    protected function discountTotal(): Attribute
    {
        return new Attribute(
            get: fn() => $this->discounts->sum('amount') + $this->items->sum('discountedAmount')
        );
    }

    protected function appliedCoupon(): Attribute
    {
        return new Attribute(
            get: fn() => $this->discounts->first(fn($d) => ! is_null($d->code))
        );
    }

    protected static function booted()
    {
        static::creating(function ($cart) {
            if ($cart->meta === null) {
                $cart->meta = [];
            }
        });
    }
}
