<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Shipment;
use Illuminate\Support\Collection;

/**
 * Monta a linha do tempo de um pedido juntando cronologicamente eventos que
 * já são registrados hoje em tabelas existentes — sem nenhuma tabela nova,
 * como pedido pela Etapa 3 da lista consolidada:
 *
 *  - Pedido criado (orders.created_at)
 *  - Cada pagamento (payments: PAID, PENDING, AUTHORIZED etc.)
 *  - Cada remessa/pré-postagem criada (shipments, com rastreio quando houver)
 *  - Cada reembolso (refunds, com motivo quando houver)
 *
 * Fora do escopo, de propósito:
 *  - Mudanças isoladas de order_status (ex.: quando foi cancelado): o projeto
 *    não guarda histórico de status — orders.updated_at muda por qualquer
 *    edição do pedido, não só por status, então não é uma data confiável
 *    para um evento específico.
 *  - orders.notes: é um campo de texto único, sobrescrito a cada edição, sem
 *    data própria de quando foi adicionado/alterado — não entra na linha do
 *    tempo por não ter um "quando" confiável.
 *
 * Espera que $order já tenha payments, shipments e refunds carregados (eager
 * load feito pelo componente Livewire) — não dispara nenhuma consulta
 * adicional aqui, respeitando o lazy loading estrito do projeto.
 */
class OrderTimelineService
{
    /**
     * @return Collection<int, array{type: string, at: \Illuminate\Support\Carbon, icon: string, color: string, title: string, description: ?string, amount: ?float}>
     */
    public function build(Order $order): Collection
    {
        $events = collect([$this->createdEvent($order)]);

        foreach ($order->payments as $payment) {
            $events->push($this->paymentEvent($payment));
        }

        foreach ($order->shipments as $shipment) {
            $events->push($this->shipmentEvent($shipment));
        }

        foreach ($order->refunds as $refund) {
            $events->push($this->refundEvent($refund));
        }

        return $events->sortByDesc('at')->values();
    }

    private function createdEvent(Order $order): array
    {
        return [
            'type' => 'created',
            'at' => $order->created_at,
            'icon' => 'heroicon-o-shopping-bag',
            'color' => 'default',
            'title' => __('Pedido criado'),
            'description' => null,
            'amount' => null,
        ];
    }

    private function paymentEvent(Payment $payment): array
    {
        $color = match ($payment->status) {
            PaymentStatus::PAID => 'success',
            PaymentStatus::AUTHORIZED, PaymentStatus::PENDING => 'warning',
            PaymentStatus::REFUNDED, PaymentStatus::PARTIALLY_REFUNDED => 'default',
            PaymentStatus::EXPIRED, PaymentStatus::OVERDUE, PaymentStatus::UNPAID => 'danger',
        };

        $channel = $payment->cash_session_id ? __('balcão (PDV)') : __('site');

        return [
            'type' => 'payment',
            'at' => $payment->created_at,
            'icon' => 'heroicon-o-credit-card',
            'color' => $color,
            'title' => $payment->status->label(),
            'description' => __('Via :channel', ['channel' => $channel]) . ($payment->reference ? ' — ' . $payment->reference : ''),
            'amount' => $payment->amount,
        ];
    }

    private function shipmentEvent(Shipment $shipment): array
    {
        $carrier = $shipment->shipping_carrier?->label() ?? __('Transportadora não informada');

        $description = $shipment->tracking_number
            ? __(':carrier — rastreio :tracking', ['carrier' => $carrier, 'tracking' => $shipment->tracking_number])
            : $carrier;

        return [
            'type' => 'shipment',
            'at' => $shipment->created_at,
            'icon' => 'heroicon-o-truck',
            'color' => 'primary',
            'title' => __('Remessa criada'),
            'description' => $description,
            'amount' => null,
        ];
    }

    private function refundEvent(Refund $refund): array
    {
        return [
            'type' => 'refund',
            'at' => $refund->created_at,
            'icon' => 'heroicon-o-arrow-uturn-left',
            'color' => 'danger',
            'title' => __('Reembolso'),
            'description' => $refund->reason,
            'amount' => $refund->amount,
        ];
    }
}