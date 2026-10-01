<?php
// app/Models/Shipment.php

namespace App\Models;

use App\Enums\ShippingCarrier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipping_carrier',
        'tracking_number',
        'tracking_url',
        'is_physical',
        'cost',
        'correios_prepostagem_id',
        'correios_status',
        'correios_label_recibo',
        'correios_remetente_manual',
        'correios_destinatario_manual',
        'shipping_box_id',
        'package_length_cm',
        'package_width_cm',
        'package_height_cm',
        'package_weight_g',
        'package_source',
        'package_estimate',
    ];

    protected $casts = [
        'shipping_carrier' => ShippingCarrier::class,
        'is_physical' => 'boolean',
        'correios_status' => 'integer',
        'correios_remetente_manual' => 'array',
        'correios_destinatario_manual' => 'array',
        'shipping_box_id' => 'integer',
        'package_length_cm' => 'integer',
        'package_width_cm' => 'integer',
        'package_height_cm' => 'integer',
        'package_weight_g' => 'integer',
        'package_estimate' => 'array',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shipmentItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function shippingBox(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShippingBox::class);
    }

    /**
     * Embalagem efetivamente informada aos Correios, ou null para envios
     * anteriores a este recurso (sem medidas registradas).
     */
    public function packageDimensions(): ?\App\Services\Shipping\PackageDimensions
    {
        if (! $this->package_length_cm) {
            return null;
        }

        return new \App\Services\Shipping\PackageDimensions(
            comprimento: $this->package_length_cm,
            largura: $this->package_width_cm,
            altura: $this->package_height_cm,
            pesoGramas: $this->package_weight_g ?? 1,
            origem: $this->package_source ?? \App\Services\Shipping\PackageDimensions::ORIGEM_MANUAL,
            volumeEstimadoCm3: (float) ($this->package_estimate['volume_estimado_cm3'] ?? 0),
            caixaId: $this->shipping_box_id,
            caixaNome: $this->shippingBox?->name,
        );
    }
}