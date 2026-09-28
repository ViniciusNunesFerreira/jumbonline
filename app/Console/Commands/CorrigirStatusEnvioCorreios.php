<?php

namespace App\Console\Commands;

use App\Events\ShipmentCreated;
use App\Listeners\UpdateOrderShippingStatus;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Console\Command;

class CorrigirStatusEnvioCorreios extends Command
{
    protected $signature = 'correios:corrigir-status-envio
        {--order= : Corrige só este número de pedido}
        {--dry-run : Só mostra o que seria corrigido, sem aplicar nada}
        {--sem-email : Atualiza o status mas não reenvia o e-mail de confirmação de envio}';

    protected $description = 'Corrige pedidos cuja pré-postagem dos Correios já foi criada, mas o status de envio nunca foi atualizado (bug corrigido em CorreiosPostagemOrchestrator)';

    public function handle()
    {
        $query = Shipment::query()
            ->where('shipping_carrier', 'correios')
            ->whereDoesntHave('shipmentItems')
            ->with('order.orderItems.shipmentItems', 'order.orderItems.refundItems');

        if ($orderId = $this->option('order')) {
            $query->where('order_id', $orderId);
        }

        $shipments = $query->get();

        if ($shipments->isEmpty()) {
            $this->info('Nenhum pedido pendente de correção encontrado.');
            return self::SUCCESS;
        }

        $this->table(
            ['Pedido', 'Rastreio', 'Status atual do pedido'],
            $shipments->map(fn($s) => [$s->order_id, $s->tracking_number, $s->order->shipping_status->label() ?? $s->order->shipping_status->value])
        );

        if ($this->option('dry-run')) {
            $this->warn('Modo simulação — nada foi alterado. Rode sem --dry-run pra aplicar de verdade.');
            return self::SUCCESS;
        }

        if (! $this->confirm('Confirma a correção de ' . $shipments->count() . ' pedido(s) acima?')) {
            return self::SUCCESS;
        }

        foreach ($shipments as $shipment) {
            foreach ($shipment->order->orderItems as $item) {
                $restante = $item->quantity - ($item->shipmentItems->sum('quantity') + $item->refundItems->where('is_shipped', false)->sum('quantity'));

                if ($restante > 0) {
                    ShipmentItem::create([
                        'shipment_id' => $shipment->id,
                        'order_id' => $shipment->order_id,
                        'order_item_id' => $item->id,
                        'quantity' => $restante,
                    ]);
                }
            }

            $shipment->load('shipmentItems');

            if ($this->option('sem-email')) {
                // Só recalcula o status — pula o listener que manda e-mail.
                app(UpdateOrderShippingStatus::class)->handle(new ShipmentCreated($shipment));
            } else {
                ShipmentCreated::dispatch($shipment);
            }

            $shipment->order->refresh();

            $this->info("Pedido #{$shipment->order_id} → {$shipment->order->shipping_status->value}");
        }

        $this->info('Correção concluída.');

        return self::SUCCESS;
    }
}