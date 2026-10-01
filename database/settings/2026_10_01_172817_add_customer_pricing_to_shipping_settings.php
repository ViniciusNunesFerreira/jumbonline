<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Política de preço do frete ao cliente (site + PDV).
 *
 * customer_pricing_mode:
 *   'balcao'          → cobra a tabela de balcão dos Correios (códigos de
 *                       varejo 04014/04510); se a consulta falhar, cai no
 *                       modo contrato + margem.
 *   'contrato_margem' → cobra o preço de contrato + margem percentual, com
 *                       margem mínima em reais.
 *
 * Defaults da margem calibrados pela medição de 01/10/2026 (destino
 * 17419899, SEDEX): balcão ÷ contrato = 1,66 (300 g), 1,54 (2 kg) e
 * 1,53 (8 kg). Com 53% e mínimo de R$ 12,60 o fallback fica a menos de
 * R$ 0,20 da tabela de balcão nas três faixas.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shipping.customer_pricing_mode', 'balcao');
        $this->migrator->add('shipping.customer_markup_percent', 53.0);
        $this->migrator->add('shipping.customer_markup_minimum', 12.60);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('shipping.customer_pricing_mode');
        $this->migrator->deleteIfExists('shipping.customer_markup_percent');
        $this->migrator->deleteIfExists('shipping.customer_markup_minimum');
    }
};