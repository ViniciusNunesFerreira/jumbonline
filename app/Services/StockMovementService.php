<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Employee;
use App\Models\StockMovement;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Único ponto de entrada para qualquer mudança em variants.stock_value.
 * Toda alteração de estoque passa por aqui e gera um registro em
 * stock_movements — sem exceção.
 */
class StockMovementService
{
    public function registerSale(Variant $variant, int $quantity, $order, ?string $reason = null): void
    {
        $this->apply($variant, -$quantity, StockMovementType::VENDA, $order, $reason);
    }

    public function registerCancellation(Variant $variant, int $quantity, $order, ?string $reason = null): void
    {
        $this->apply($variant, $quantity, StockMovementType::CANCELAMENTO, $order, $reason);
    }

    public function registerReturn(Variant $variant, int $quantity, $refund, ?string $reason = null): void
    {
        $this->apply($variant, $quantity, StockMovementType::DEVOLUCAO, $refund, $reason);
    }

    public function registerEntry(Variant $variant, int $quantity, ?Employee $employee = null, ?string $reason = null): void
    {
        $this->apply($variant, $quantity, StockMovementType::ENTRADA, null, $reason, $employee);
    }

    public function registerLoss(Variant $variant, int $quantity, ?Employee $employee = null, ?string $reason = null): void
    {
        $this->apply($variant, -$quantity, StockMovementType::PERDA, null, $reason, $employee);
    }

    public function registerAdjustment(Variant $variant, int $newQuantity, ?Employee $employee = null, ?string $reason = null): void
    {
        $delta = $newQuantity - $variant->stock_value;

        if ($delta === 0) {
            return;
        }

        $this->apply($variant, $delta, StockMovementType::AJUSTE, null, $reason, $employee);
    }

    protected function apply(Variant $variant, int $delta, StockMovementType $type, $reference = null, ?string $reason = null, ?Employee $employee = null): void
    {
        if (! $variant->stock_tracking) {
            return;
        }

        DB::transaction(function () use ($variant, $delta, $type, $reference, $reason, $employee) {
            $locked = Variant::whereKey($variant->id)->lockForUpdate()->first();

            if (! $locked) {
                return;
            }

            $locked->increment('stock_value', $delta);

            StockMovement::create([
                'variant_id' => $locked->id,
                'type' => $type,
                'quantity' => abs($delta),
                'reason' => $reason,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->id,
                'employee_id' => $employee?->id,
            ]);

            if ($locked->stock_value < 0) {
                Log::warning("Estoque negativo após movimentação: variant #{$locked->id} ({$locked->sku}) ficou com {$locked->stock_value} unidades.");
            }
        });

        $variant->refresh();
    }
}