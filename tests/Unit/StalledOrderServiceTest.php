<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use App\Models\Order;
use App\Services\StalledOrderService;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Cobre StalledOrderService::rowInfo(), que decide se uma linha da lista de
 * Pedidos é sinalizada como "parada" a partir de atributos já carregados
 * (payment_status, shipping_status, order_status e o agregado
 * paid_payments_min_created_at). Não acessa banco de dados: os pedidos são
 * construídos em memória com os atributos já preenchidos, do mesmo jeito
 * que o Eloquent os entregaria depois de um ->with()/->withMin() real.
 *
 * O limite de dias é injetado via construtor (ver StalledOrderService),
 * então o teste nunca precisa resolver App\Settings\OrderSetting nem
 * tocar a tabela settings.
 */
class StalledOrderServiceTest extends TestCase
{
    private const THRESHOLD_DAYS = 2;

    private StalledOrderService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-09-29 12:00:00'));

        $this->service = new StalledOrderService(self::THRESHOLD_DAYS);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function makeOrder(array $attributes): Order
    {
        $order = new Order();

        $order->forceFill(array_merge([
            'payment_status' => PaymentStatus::PAID->name,
            'shipping_status' => ShippingStatus::UNSHIPPED->value,
            'order_status' => OrderStatus::OPEN->name,
        ], $attributes));

        return $order;
    }

    public function test_threshold_days_uses_the_constructor_override(): void
    {
        $this->assertSame(self::THRESHOLD_DAYS, $this->service->thresholdDays());
    }

    public function test_cutoff_is_now_minus_threshold_days(): void
    {
        $this->assertTrue($this->service->cutoff()->equalTo(now()->subDays(self::THRESHOLD_DAYS)));
    }

    public function test_order_paid_and_over_threshold_is_stalled(): void
    {
        $order = $this->makeOrder([
            'paid_payments_min_created_at' => now()->subDays(3)->toDateTimeString(),
        ]);

        $result = $this->service->rowInfo($order);

        $this->assertNotNull($result);
        $this->assertSame(3, $result['days']);
        $this->assertInstanceOf(Carbon::class, $result['paid_at']);
    }

    public function test_order_paid_exactly_at_threshold_is_stalled(): void
    {
        $order = $this->makeOrder([
            'paid_payments_min_created_at' => now()->subDays(self::THRESHOLD_DAYS)->toDateTimeString(),
        ]);

        $result = $this->service->rowInfo($order);

        $this->assertNotNull($result);
        $this->assertSame(self::THRESHOLD_DAYS, $result['days']);
    }

    public function test_order_paid_recently_is_not_stalled(): void
    {
        $order = $this->makeOrder([
            'paid_payments_min_created_at' => now()->subHours(5)->toDateTimeString(),
        ]);

        $this->assertNull($this->service->rowInfo($order));
    }

    public function test_unpaid_order_is_never_stalled(): void
    {
        $order = $this->makeOrder([
            'payment_status' => PaymentStatus::PENDING->name,
            'paid_payments_min_created_at' => now()->subDays(10)->toDateTimeString(),
        ]);

        $this->assertNull($this->service->rowInfo($order));
    }

    public function test_already_shipped_order_is_not_stalled(): void
    {
        $order = $this->makeOrder([
            'shipping_status' => ShippingStatus::SHIPPED->value,
            'paid_payments_min_created_at' => now()->subDays(10)->toDateTimeString(),
        ]);

        $this->assertNull($this->service->rowInfo($order));
    }

    public function test_partially_shipped_order_is_not_stalled(): void
    {
        $order = $this->makeOrder([
            'shipping_status' => ShippingStatus::PARTIALLY_SHIPPED->value,
            'paid_payments_min_created_at' => now()->subDays(10)->toDateTimeString(),
        ]);

        $this->assertNull($this->service->rowInfo($order));
    }

    public function test_cancelled_order_is_never_stalled(): void
    {
        $order = $this->makeOrder([
            'order_status' => OrderStatus::CANCELLED->name,
            'paid_payments_min_created_at' => now()->subDays(10)->toDateTimeString(),
        ]);

        $this->assertNull($this->service->rowInfo($order));
    }

    public function test_archived_order_is_never_stalled(): void
    {
        $order = $this->makeOrder([
            'order_status' => OrderStatus::ARCHIVED->name,
            'paid_payments_min_created_at' => now()->subDays(10)->toDateTimeString(),
        ]);

        $this->assertNull($this->service->rowInfo($order));
    }

    /**
     * Dado legado/correção manual: payment_status = PAID mas sem nenhum
     * registro em payments (paid_payments_min_created_at nulo). Não deve
     * quebrar nem ser sinalizado — não há data de referência confiável.
     */
    public function test_paid_order_without_any_payment_record_is_not_stalled(): void
    {
        $order = $this->makeOrder([
            'paid_payments_min_created_at' => null,
        ]);

        $this->assertNull($this->service->rowInfo($order));
    }
}