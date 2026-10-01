<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ShippingBox extends Model
{
    protected $fillable = [
        'name',
        'code',
        'length_cm',
        'width_cm',
        'height_cm',
        'max_weight_g',
        'is_active',
    ];

    protected $casts = [
        'length_cm' => 'integer',
        'width_cm' => 'integer',
        'height_cm' => 'integer',
        'max_weight_g' => 'integer',
        'is_active' => 'boolean',
    ];

    public function shipments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Volume interno em cm³.
     */
    protected function volumeCm3(): Attribute
    {
        return Attribute::make(
            get: fn () => (int) $this->length_cm * (int) $this->width_cm * (int) $this->height_cm
        );
    }

    /**
     * Peso cúbico dos Correios (C × L × A / 6000), em kg.
     */
    protected function cubicWeightKg(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->volume_cm3 / 6000, 2)
        );
    }

    protected function dimensionsLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->length_cm} × {$this->width_cm} × {$this->height_cm} cm"
        );
    }
}