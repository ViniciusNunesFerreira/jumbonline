<?php
// app/Settings/ShippingSetting.php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Parâmetros de frete.
 *
 * Estimativa volumétrica de embalagem (PackageEstimator):
 * - estimated_density: densidade aparente média do catálogo, em g/cm³.
 *   Quanto MENOR, maior o volume estimado (estimativa mais conservadora).
 * - packaging_margin_percent: folga para embalagem/proteção somada ao
 *   volume estimado dos itens.
 *
 * Preço do frete ao cliente — site e PDV (CustomerFreightPricing):
 * - customer_pricing_mode: 'balcao' ou 'contrato_margem'.
 * - customer_markup_percent: margem sobre o preço de contrato, em %.
 * - customer_markup_minimum: margem mínima por envio, em R$.
 */
class ShippingSetting extends Settings
{
    public float $estimated_density;

    public int $packaging_margin_percent;

    public string $customer_pricing_mode;

    public float $customer_markup_percent;

    public float $customer_markup_minimum;

    public static function group(): string
    {
        return 'shipping';
    }
}