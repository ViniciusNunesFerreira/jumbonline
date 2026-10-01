<?php
// app/Services/Shipping/CustomerFreightPricing.php

namespace App\Services\Shipping;

use App\Enums\ShippingServices;
use App\Services\CorreiosFreightService;
use App\Settings\ShippingSetting;
use Illuminate\Support\Facades\Log;

/**
 * Política ÚNICA de preço do frete ao cliente — checkout do site e PDV.
 *
 * Regra comercial: o cliente paga o preço de balcão (cliente final) dos
 * Correios. O desconto do contrato fica com a Jumbonline para cobrir os
 * custos internos (embalagem, transporte até a agência).
 *
 * Modo 'balcao' (padrão): consulta o código de varejo equivalente ao
 * serviço (03220 → 04014, 03298 → 04510) com o mesmo pacote estimado. Uma
 * única chamada à API no caminho normal. Se a consulta falhar, cai no modo
 * abaixo e registra aviso no log — o checkout nunca fica sem frete por isso.
 *
 * Modo 'contrato_margem': preço de contrato + max(percentual, mínimo em R$).
 * O mínimo protege os pedidos pequenos, cujo custo interno (caixa, fita,
 * deslocamento) é quase fixo e que uma margem só percentual não cobre.
 *
 * Substitui o "+46%" que estava duplicado em Purchase (arredondado em
 * reais) e em CorreiosFreightService (arredondado em centavos).
 */
class CustomerFreightPricing
{
    public const MODO_BALCAO = 'balcao';

    public const MODO_CONTRATO_MARGEM = 'contrato_margem';

    public const DEFAULT_MARKUP_PERCENT = 53.0;

    public const DEFAULT_MARKUP_MINIMUM = 12.60;

    private ?array $politica = null;

    public function __construct(private CorreiosFreightService $correios)
    {
    }

    /**
     * Preço ao cliente para o envio, ou null se os Correios não responderem
     * nem no modo principal nem no fallback.
     *
     * @return array{preco: float, origem: string, referencia: float, servico: string}|null
     */
    public function cotar(string $cepOrigem, string $cepDestino, float $pesoGramas, ShippingServices $servicoContrato, ?PackageDimensions $pacote = null): ?array
    {
        $pacote ??= app(PackageEstimator::class)->estimarPorPeso($pesoGramas);
        $politica = $this->politica();

        if ($politica['modo'] === self::MODO_BALCAO && $servicoContrato->varejo()) {
            $varejo = $servicoContrato->varejo();
            $balcao = $this->correios->consultarPrecoCorreios($cepOrigem, $cepDestino, $pesoGramas, $varejo, $pacote);

            if ($balcao !== null) {
                return [
                    'preco' => round($balcao, 2),
                    'origem' => self::MODO_BALCAO,
                    'referencia' => round($balcao, 2),
                    'servico' => $varejo->value,
                ];
            }

            Log::warning('[CustomerFreightPricing] Tabela de balcão indisponível — usando contrato + margem.', [
                'servico' => $varejo->value,
                'cepDestino' => $cepDestino,
                'pacote' => $pacote->toArray(),
            ]);
        }

        $contrato = $this->correios->consultarPrecoCorreios($cepOrigem, $cepDestino, $pesoGramas, $servicoContrato, $pacote);

        if ($contrato === null) {
            Log::error('[CustomerFreightPricing] Correios sem preço (balcão e contrato) — frete do pedido ficará zerado.', [
                'servico' => $servicoContrato->value,
                'cepDestino' => $cepDestino,
                'pacote' => $pacote->toArray(),
            ]);

            return null;
        }

        return [
            'preco' => self::precoPorMargem($contrato, $politica['percentual'], $politica['minimo']),
            'origem' => self::MODO_CONTRATO_MARGEM,
            'referencia' => round($contrato, 2),
            'servico' => $servicoContrato->value,
        ];
    }

    /**
     * Preço de contrato + max(percentual, mínimo), em centavos.
     */
    public static function precoPorMargem(float $contrato, float $percentual, float $minimo): float
    {
        $margem = max(round(($contrato * $percentual) / 100, 2), round($minimo, 2));

        return round($contrato + $margem, 2);
    }

    /**
     * Política vigente; settings ausentes (migration não executada) caem
     * nos defaults, sem nunca interromper o checkout.
     *
     * @return array{modo: string, percentual: float, minimo: float}
     */
    public function politica(): array
    {
        if ($this->politica !== null) {
            return $this->politica;
        }

        $politica = [
            'modo' => self::MODO_BALCAO,
            'percentual' => self::DEFAULT_MARKUP_PERCENT,
            'minimo' => self::DEFAULT_MARKUP_MINIMUM,
        ];

        try {
            $settings = app(ShippingSetting::class);

            $politica = [
                'modo' => in_array($settings->customer_pricing_mode, [self::MODO_BALCAO, self::MODO_CONTRATO_MARGEM], true)
                    ? $settings->customer_pricing_mode
                    : self::MODO_BALCAO,
                'percentual' => max(0, (float) $settings->customer_markup_percent),
                'minimo' => max(0, (float) $settings->customer_markup_minimum),
            ];
        } catch (\Throwable $e) {
            Log::warning('[CustomerFreightPricing] ShippingSetting indisponível — usando política padrão.', ['error' => $e->getMessage()]);
        }

        return $this->politica = $politica;
    }
}