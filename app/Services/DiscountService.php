<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartDiscount;
use App\Models\Discount;
use App\Enums\ProductType;
use RuntimeException;

class DiscountService
{
    /**
     * Cliente digitou um código no carrinho — valida e aplica de verdade.
     */
    public function redeemCode(Cart $cart, string $code): Discount
    {
        // Só um cupom por vez — evita acumular vários códigos e gerar um
        // desconto muito maior do que a equipe comercial pretendia.
        // Promoções automáticas continuam podendo coexistir, já que essas
        // são decisão do negócio, não escolha livre do cliente.
        if ($cart->discounts()->whereNotNull('code')->exists()) {
            throw new RuntimeException('Você já tem um cupom aplicado neste carrinho. Remova-o antes de aplicar outro.');
        }

        $discount = Discount::where('code', $code)->whereNotNull('code')->first();

        if (! $discount) {
            throw new RuntimeException('Código de desconto inválido.');
        }

        $this->assertUsable($discount);

        $this->apply($cart, $discount);

        return $discount;
    }

    /**
     * Remove só o cupom digitado pelo cliente — promoções automáticas
     * continuam intactas.
     */
    public function removeCode(Cart $cart): void
    {
        $cart->discounts()->whereNotNull('code')->delete();
    }

    /**
     * Roda a cada carregamento do carrinho (mesmo padrão já usado pela
     * promoção de frete grátis em Purchase::updateShippingPrice()) — aplica
     * sozinho, sem o cliente precisar fazer nada, qualquer promoção
     * automática (sem código) que esteja dentro da validade.
     */
    public function applyAutomaticDiscounts(Cart $cart): void
    {
        $automaticos = Discount::whereNull('code')
            ->where('starts_at', '<=', now())
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->get();

        foreach ($automaticos as $discount) {
            if (! $this->isUsable($discount)) {
                continue;
            }

            if ($cart->discounts()->where('discount_id', $discount->id)->exists()) {
                continue;
            }

            $this->apply($cart, $discount);
        }
    }

    protected function apply(Cart $cart, Discount $discount): void
    {
        if ($discount->applies_to === 'orders') {
            $cart->discounts()->create([
                'cart_item_id' => null,
                'discount_id' => $discount->id,
                'code' => $discount->code,
                'type' => $discount->type,
                'amount' => $discount->type === 'fixed'
                    ? $discount->value
                    : round($cart->subtotal * $discount->value / 100, 2),
            ]);

            return;
        }

        $itemsElegiveis = $this->itensElegiveis($cart, $discount);

        foreach ($itemsElegiveis as $item) {
            // Um item só pode ter um desconto por vez (CartItem::discount() é hasOne).
            if ($item->discount) {
                continue;
            }

            $cart->discounts()->create([
                'cart_item_id' => $item->id,
                'discount_id' => $discount->id,
                'code' => $discount->code,
                'type' => $discount->type,
                'amount' => $discount->type === 'fixed'
                    ? $discount->value
                    : round($item->subtotal * $discount->value / 100, 2),
            ]);
        }
    }

    protected function itensElegiveis(Cart $cart, Discount $discount)
    {
        return $cart->items->filter(function ($item) use ($discount) {
            if (! $item->variant) {
                return false;
            }

            $product = $item->variant->product;

            // Desconto só se aplica a Kits — produto Simples nunca é vendido
            // avulso, então nunca faz sentido descontar um Simples sozinho.
            if ($product->type !== ProductType::KIT) {
                return false;
            }

            if ($discount->applies_to === 'products') {
                return $discount->products->contains('id', $product->id);
            }

            if ($discount->applies_to === 'collections') {
                return $product->categories->pluck('id')->intersect($discount->collections->pluck('id'))->isNotEmpty();
            }

            return false;
        });
    }

    protected function assertUsable(Discount $discount): void
    {
        if (! $this->isUsable($discount)) {
            throw new RuntimeException('Este desconto não está mais disponível.');
        }
    }

    protected function isUsable(Discount $discount): bool
    {
        if ($discount->starts_at && $discount->starts_at->isFuture()) {
            return false;
        }

        if ($discount->ends_at && $discount->ends_at->isPast()) {
            return false;
        }

        if ($discount->usage_limit && $discount->usage_count >= $discount->usage_limit) {
            return false;
        }

        return true;
    }
}