<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * Parâmetros da estimativa volumétrica de embalagem (PackageEstimator).
 *
 * estimated_density: densidade aparente média do catálogo, em g/cm³.
 * Quanto MENOR, maior o volume estimado (estimativa mais conservadora).
 *
 * packaging_margin_percent: folga para embalagem/proteção somada ao
 * volume estimado dos itens.
 */
class ShippingSetting extends Settings
{
    public float $estimated_density;

    public int $packaging_margin_percent;

    public static function group(): string
    {
        return 'shipping';
    }
}