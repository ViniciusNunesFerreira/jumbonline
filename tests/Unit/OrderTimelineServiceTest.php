<?php

// tests/Unit/OrderTimelineServiceTest.php

namespace Tests\Unit;

use App\Enums\PaymentStatus;
use App\Enums\ShippingCarrier;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Shipment;
use App\Services\OrderTimelineService;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Cobre OrderTimelineService::build(), que junta cronologicamente pedido
 * criado + pagamentos + remessas + reembolsos. Não acessa banco de dados:
 * o pedido e suas relações (payments/shipments/refunds) são construídos em
 * memória e associados via setRelation(), do jeito que o Eloquent os
 * entregaria depois de um ->load() real.
 */
class OrderTimelineServiceTest extends TestCase
{
    private OrderTimelineService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new OrderTimelineService();
    }

    private function makeOrder(Carbon $createdAt, array $payments = [], array $shipments = [], array $refunds = []): Order
    {
        $order = new Order();
        $order->forceFill(['created_at' => $createdAt]);

        $order->setRelation('payments', collect($payments));
        $order->setRelation('shipments', collect($shipments));
        $order->setRelation('refunds', collect($refunds));

        return $order;
    }

    private function makePayment(array $attributes): Payment
    {
        $payment = new Payment();
        $payment->forceFill(array_merge([
            'status' => PaymentStatus::PAID->name,
            'amount' => 100.0,
            'currency' => 'BRL',
        ], $attributes));

        return $payment;
    }

    private function makeShipment(array $attributes): Shipment
    {
        $shipment = new Shipment();
        $shipment->forceFill(array_merge([
            'shipping_carrier' => ShippingCarrier::CORREIOS->value,
        ], $attributes));

        return $shipment;
    }

    private function makeRefund(array $attributes): Refund
    {
        $refund = new Refund();
        $refund->forceFill(array_merge([
            'amount' => 50.0,
        ], $attributes));

        return $refund;
    }

    public function test_order_with_no_related_records_has_only_the_created_event(): void
    {
        $order = $this->makeOrder(Carbon::parse('2026-09-01 10:00:00'));

        $events = $this->service->build($order);

        $this->assertCount(1, $events);
        $this->assertSame('created', $events->first()['type']);
        $this->assertNull($events->first()['amount']);
    }

    public function test_events_are_sorted_from_newest_to_oldest(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            payments: [$this->makePayment(['created_at' => Carbon::parse('2026-09-02 10:00:00')])],
            shipments: [$this->makeShipment(['created_at' => Carbon::parse('2026-09-04 10:00:00'), 'tracking_number' => 'AB123456789BR'])],
            refunds: [$this->makeRefund(['created_at' => Carbon::parse('2026-09-03 10:00:00')])],
        );

        $events = $this->service->build($order);

        $this->assertCount(4, $events);
        $this->assertSame(['shipment', 'refund', 'payment', 'created'], $events->pluck('type')->all());
    }

    public function test_paid_payment_is_colored_as_success_and_carries_the_amount(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            payments: [$this->makePayment(['status' => PaymentStatus::PAID->name, 'amount' => 199.9, 'created_at' => now()])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'payment');

        $this->assertSame('success', $event['color']);
        $this->assertSame(199.9, $event['amount']);
    }

    public function test_pending_payment_is_colored_as_warning(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            payments: [$this->makePayment(['status' => PaymentStatus::PENDING->name, 'created_at' => now()])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'payment');

        $this->assertSame('warning', $event['color']);
    }

    public function test_unpaid_payment_is_colored_as_danger(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            payments: [$this->makePayment(['status' => PaymentStatus::UNPAID->name, 'created_at' => now()])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'payment');

        $this->assertSame('danger', $event['color']);
    }

    public function test_refunded_payment_is_colored_as_default(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            payments: [$this->makePayment(['status' => PaymentStatus::REFUNDED->name, 'created_at' => now()])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'payment');

        $this->assertSame('default', $event['color']);
    }

    public function test_pdv_payment_channel_is_shown_in_the_description(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            payments: [$this->makePayment(['cash_session_id' => 42, 'created_at' => now()])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'payment');

        $this->assertStringContainsString('balcão', $event['description']);
    }

    public function test_shipment_with_tracking_number_includes_it_in_the_description(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            shipments: [$this->makeShipment(['created_at' => now(), 'tracking_number' => 'AB123456789BR'])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'shipment');

        $this->assertStringContainsString('AB123456789BR', $event['description']);
        $this->assertStringContainsString('Correios', $event['description']);
    }

    public function test_shipment_without_tracking_number_falls_back_to_carrier_only(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            shipments: [$this->makeShipment(['created_at' => now(), 'tracking_number' => null, 'shipping_carrier' => ShippingCarrier::OTHER->value])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'shipment');

        $this->assertSame('Transportadora', $event['description']);
    }

    public function test_refund_carries_amount_and_reason(): void
    {
        $order = $this->makeOrder(
            createdAt: Carbon::parse('2026-09-01 10:00:00'),
            refunds: [$this->makeRefund(['amount' => 75.5, 'reason' => 'Item avariado', 'created_at' => now()])],
        );

        $event = $this->service->build($order)->firstWhere('type', 'refund');

        $this->assertSame(75.5, $event['amount']);
        $this->assertSame('Item avariado', $event['description']);
    }
}