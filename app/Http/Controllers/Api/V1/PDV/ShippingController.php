<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\PrisonUnit;
use App\Models\Product;
use App\Services\CorreiosFreightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * CEP de origem da loja/depósito, usado como ponto de partida do
     * cálculo de frete. Mesmo valor fixo já usado pelo checkout do site em
     * Purchase::updateShippingPrice() — mantido idêntico para não gerar
     * divergência de precificação entre site e PDV.
     */
    private const ORIGIN_CEP = '02737050';

    /**
     * Peso mínimo (kg) usado quando um item não tem peso cadastrado na sua
     * variante, para evitar cotação com peso zero. O ideal é o cadastro de
     * produto sempre informar o peso — isto é apenas uma rede de segurança.
     */
    private const DEFAULT_ITEM_WEIGHT_KG = 0.3;

    public function __construct(private readonly CorreiosFreightService $freightService)
    {
    }

    /**
     * Cota frete (PAC e SEDEX) para os itens do carrinho até a Unidade
     * Prisional informada.
     *
     * POST /api/v1/pdv/shipping/quote
     * { "prison_unit_id": 1, "items": [{"product_id": 10, "quantity": 2}] }
     */
    public function quote(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prison_unit_id' => ['required', 'integer', 'exists:prison_units,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $prisonUnit = PrisonUnit::findOrFail($validated['prison_unit_id']);
        $destCep = preg_replace('/\D/', '', (string) $prisonUnit->cep);

        if (strlen($destCep) !== 8) {
            return response()->json([
                'success' => false,
                'message' => 'A Unidade Prisional selecionada não possui um CEP válido cadastrado. Não é possível calcular o frete.',
            ], 422);
        }

        $weightKg = $this->calculateItemsWeight($validated['items']);
        $weightGrams = $weightKg * 1000;

        $quotes = $this->freightService->quoteAll(self::ORIGIN_CEP, $destCep, $weightGrams);

        if (empty($quotes)) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível calcular o frete no momento (Correios indisponível). Você pode tentar novamente ou informar um valor manualmente.',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'data' => $quotes,
        ]);
    }

    /**
     * Mesma lógica de peso usada em PDVOrderController@store (Product com
     * first_variant) e em Cart::weight() no checkout do site, reaproveitada
     * aqui para manter os três pontos consistentes.
     */
    private function calculateItemsWeight(array $items): float
    {
        $totalWeightKg = 0.0;

        foreach ($items as $item) {
            $product = Product::with('first_variant')->find($item['product_id']);

            if (!$product) {
                continue;
            }

            $variant = $product->first_variant;
            $itemWeightKg = self::DEFAULT_ITEM_WEIGHT_KG;

            if ($variant && $variant->weight_value) {
                $itemWeightKg = $variant->weight_unit === 'g'
                    ? $variant->weight_value / 1000
                    : (float) $variant->weight_value;
            }

            $totalWeightKg += $itemWeightKg * (int) $item['quantity'];
        }

        // Nunca cota com peso zero (a API dos Correios rejeita o pedido)
        return max($totalWeightKg, self::DEFAULT_ITEM_WEIGHT_KG);
    }
}
