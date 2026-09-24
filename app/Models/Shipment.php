<?php

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
    ];

    protected $casts = [
        'shipping_carrier' => ShippingCarrier::class,
        'is_physical' => 'boolean',
        'correios_status' => 'integer',
        'correios_remetente_manual' => 'array',
        'correios_destinatario_manual' => 'array',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shipmentItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }
}