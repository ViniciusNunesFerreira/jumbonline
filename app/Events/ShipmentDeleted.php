<?php
// app/Events/ShipmentDeleted.php

namespace App\Events;

use App\Models\Shipment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Remessa excluída (ex.: pré-postagem cancelada nos Correios).
 *
 * Já estava registrado no EventServiceProvider (listener
 * UpdateOrderShippingStatus), mas a classe não existia — o cancelamento
 * quebrava com "Class App\Events\ShipmentDeleted not found" DEPOIS de já ter
 * cancelado nos Correios. Deve ser disparado APÓS o delete, para o listener
 * recalcular o status de envio do pedido já sem esta remessa.
 */
class ShipmentDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Shipment $shipment)
    {
    }
}