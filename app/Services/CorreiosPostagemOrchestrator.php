<?php

namespace App\Services;

use App\Enums\CorreiosPrepostagemStatus;
use App\Enums\ShippingCarrier;
use App\Events\ShipmentCreated;
use App\Jobs\AguardarStatusPrepostagemJob;
use App\Jobs\SolicitarRotuloCorreiosJob;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use RuntimeException;


class CorreiosPostagemOrchestrator
{
    public function __construct(protected CorreiosPrepostagemService $service)
    {
    }

    public function criar(int $orderId, ?array $remetenteManual = null, ?array $destinatarioManual = null): Shipment
    {
        if (Shipment::where('order_id', $orderId)->exists()) {
            throw new RuntimeException('Este pedido já tem uma remessa registrada — nada foi criado de novo.');
        }

        $order = Order::with([
            'orderItems.variant',
            'orderItems.shipmentItems',
            'orderItems.refundItems' => fn($query) => $query->where('is_shipped', false),
            'visitante',
            'detento',
            'prison_unit',
        ])->findOrFail($orderId);

        $data = $this->service->criar($order, $remetenteManual, $destinatarioManual);

        if (Shipment::where('order_id', $orderId)->exists()) {
            throw new RuntimeException("Atenção: uma pré-postagem foi criada nos Correios (id {$data['id']}, objeto {$data['codigoObjeto']}) mas o pedido #{$orderId} já tinha uma remessa registrada. Cancele manualmente essa pré-postagem duplicada na tela de processamento ou direto no CWS.");
        }

        $shipment = Shipment::create([
            'order_id' => $order->id,
            'shipping_carrier' => ShippingCarrier::CORREIOS->value,
            'is_physical' => true,
            'tracking_number' => $data['codigoObjeto'] ?? null,
            'cost' => $data['precoPrePostagem'] ?? null,
            'correios_prepostagem_id' => $data['id'],
            'correios_status' => $data['statusAtual'] ?? null,
            'correios_remetente_manual' => $remetenteManual,
            'correios_destinatario_manual' => $destinatarioManual,
        ]);


        foreach ($order->orderItems as $item) {
            $restante = $item->quantity - ($item->shipmentItems->sum('quantity') + $item->refundItems->sum('quantity'));

            if ($restante > 0) {
                ShipmentItem::create([
                    'shipment_id' => $shipment->id,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'quantity' => $restante,
                ]);
            }
        }


        ShipmentCreated::dispatch($shipment);

        if ($shipment->correios_status === CorreiosPrepostagemStatus::PENDENTE->value) {
            AguardarStatusPrepostagemJob::dispatch($shipment->id)->delay(now()->addSeconds(5));
        } else {
            SolicitarRotuloCorreiosJob::dispatch($shipment->id);
        }

        return $shipment;
    }
}