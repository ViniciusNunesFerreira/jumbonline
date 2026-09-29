<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use App\Models\Order;
use App\Settings\OrderSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Regra de "pedido parado": pedido com pagamento confirmado há mais de N dias
 * (configurável em Configurações > Pedidos) que ainda não teve nenhuma
 * remessa (Shipment) criada — ou seja, nenhuma pré-postagem/envio.
 *
 * Usado hoje pela lista de Pedidos (badge por linha + filtro "somente
 * parados"). O item 7 da lista consolidada (seção operacional do Dashboard)
 * reaproveita este mesmo serviço sem nenhuma alteração — count() e
 * scopeStalled() já são genéricos o bastante para isso.
 *
 * Definição de "pago": existe pelo menos um registro em payments com
 * status PAID. A data de referência é a confirmação MAIS ANTIGA — é desde
 * esse momento que o pedido está elegível para envio.
 *
 * Pedidos CANCELLED ou ARCHIVED nunca são considerados parados, mesmo que
 * tecnicamente pagos e sem remessa (não é uma situação a resolver).
 */
class StalledOrderService
{
    /**
     * @param  int|null  $thresholdDaysOverride  Usado apenas em testes, para não
     *                                            depender da tabela de settings.
     */
    public function __construct(protected ?int $thresholdDaysOverride = null)
    {
    }

    public function thresholdDays(): int
    {
        return $this->thresholdDaysOverride ?? app(OrderSetting::class)->stalled_order_days_threshold;
    }

    public function cutoff(): Carbon
    {
        return now()->subDays($this->thresholdDays());
    }

    /**
     * Aplica a regra de "pedido parado" a uma query de Order já existente.
     * Não faz nenhuma alteração se a query já tiver outros filtros — apenas
     * encadeia via where/whereHas, como o restante dos filtros do projeto.
     */
    public function scopeStalled(Builder $query): Builder
    {
        $cutoff = $this->cutoff();

        return $query
            ->where('payment_status', PaymentStatus::PAID->name)
            ->where('shipping_status', ShippingStatus::UNSHIPPED->value)
            ->whereNotIn('order_status', [OrderStatus::CANCELLED->name, OrderStatus::ARCHIVED->name])
            ->whereHas('paidPayments', fn (Builder $q) => $q->where('created_at', '<=', $cutoff));
    }

    /**
     * Quantidade total de pedidos parados agora, independente de qualquer
     * filtro/busca ativo na tela — usado no banner de alerta.
     */
    public function count(): int
    {
        return $this->scopeStalled(Order::query())->count();
    }

    /**
     * Informações de exibição para uma linha da lista, a partir de dados
     * já carregados (payment_status, shipping_status, order_status e o
     * atributo agregado paid_payments_min_created_at via withMin). Não
     * dispara nenhuma consulta adicional — respeita o lazy loading estrito.
     *
     * @return array{days: int, paid_at: Carbon}|null
     */
    public function rowInfo(Order $order): ?array
    {
        if ($order->payment_status !== PaymentStatus::PAID) {
            return null;
        }

        if ($order->shipping_status !== ShippingStatus::UNSHIPPED) {
            return null;
        }

        if (in_array($order->order_status, [OrderStatus::CANCELLED, OrderStatus::ARCHIVED], true)) {
            return null;
        }

        $paidAtRaw = $order->getAttribute('paid_payments_min_created_at');

        // Pedido marcado como PAID sem nenhum registro em payments (dado legado
        // ou correção manual) — não há data de referência, não arriscamos um
        // cálculo de dias incorreto. Ver engineering-principles.md sobre nunca
        // deixar lógica auxiliar quebrar por dado histórico incompleto.
        if (! $paidAtRaw) {
            return null;
        }

        $paidAt = $paidAtRaw instanceof Carbon ? $paidAtRaw : Carbon::parse($paidAtRaw);
        $days = $paidAt->diffInDays(now());

        if ($days < $this->thresholdDays()) {
            return null;
        }

        return [
            'days' => $days,
            'paid_at' => $paidAt,
        ];
    }
}