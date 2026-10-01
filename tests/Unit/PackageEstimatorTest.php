<?php

namespace Tests\Unit;

use App\Services\Shipping\PackageDimensions;
use App\Services\Shipping\PackageEstimator;
use Tests\TestCase;

/**
 * Cobre o algoritmo do PackageEstimator sem banco nem settings: densidade,
 * margem e caixas são injetadas pelo construtor (mesmo padrão de
 * StalledOrderServiceTest).
 */
class PackageEstimatorTest extends TestCase
{
    private const CAIXAS = [
        ['id' => 1, 'name' => 'Caixa PP', 'length_cm' => 16, 'width_cm' => 11, 'height_cm' => 6, 'max_weight_g' => 1000],
        ['id' => 2, 'name' => 'Caixa P', 'length_cm' => 24, 'width_cm' => 16, 'height_cm' => 10, 'max_weight_g' => 3000],
        ['id' => 3, 'name' => 'Caixa M', 'length_cm' => 30, 'width_cm' => 22, 'height_cm' => 15, 'max_weight_g' => 6000],
        ['id' => 4, 'name' => 'Caixa G', 'length_cm' => 40, 'width_cm' => 30, 'height_cm' => 25, 'max_weight_g' => 12000],
        ['id' => 5, 'name' => 'Caixa GG (jumbo)', 'length_cm' => 54, 'width_cm' => 36, 'height_cm' => 27, 'max_weight_g' => 30000],
    ];

    private function estimador(array $caixas = self::CAIXAS): PackageEstimator
    {
        return new PackageEstimator(0.22, 12, $caixas);
    }

    public function test_volume_estimado_aplica_densidade_e_margem(): void
    {
        // 500 g ÷ 0,22 g/cm³ × 1,12 = 2.545,45 cm³
        $this->assertEqualsWithDelta(2545.45, $this->estimador()->volumeEstimado(500), 0.01);
    }

    public function test_produto_pequeno_vai_para_a_menor_caixa(): void
    {
        $pacote = $this->estimador()->estimarPorPeso(150);

        $this->assertSame(PackageDimensions::ORIGEM_CAIXA, $pacote->origem);
        $this->assertSame(1, $pacote->caixaId);
        $this->assertSame([16, 11, 6], [$pacote->comprimento, $pacote->largura, $pacote->altura]);
        $this->assertFalse($pacote->excedeCapacidade);
        $this->assertLessThan(1, $pacote->pesoCubicoKg());
    }

    public function test_escolhe_a_menor_caixa_que_comporta_o_volume(): void
    {
        $this->assertSame(2, $this->estimador()->estimarPorPeso(500)->caixaId);
        $this->assertSame(3, $this->estimador()->estimarPorPeso(1500)->caixaId);
        $this->assertSame(4, $this->estimador()->estimarPorPeso(3000)->caixaId);
        $this->assertSame(5, $this->estimador()->estimarPorPeso(6000)->caixaId);
    }

    public function test_ordem_de_cadastro_das_caixas_nao_importa(): void
    {
        $pacote = $this->estimador(array_reverse(self::CAIXAS))->estimarPorPeso(500);

        $this->assertSame(2, $pacote->caixaId);
    }

    public function test_respeita_peso_maximo_da_caixa(): void
    {
        $caixas = self::CAIXAS;
        $caixas[1]['max_weight_g'] = 400; // Caixa P comporta o volume de 500 g, mas não o peso

        $this->assertSame(3, $this->estimador($caixas)->estimarPorPeso(500)->caixaId);
    }

    public function test_pedido_acima_da_maior_caixa_usa_a_maior_e_sinaliza(): void
    {
        $pacote = $this->estimador()->estimarPorPeso(12000);

        $this->assertSame(5, $pacote->caixaId);
        $this->assertTrue($pacote->excedeCapacidade);
    }

    public function test_sem_caixas_usa_estimativa_proporcional_com_minimos_dos_correios(): void
    {
        $pacote = $this->estimador([])->estimarPorPeso(1);

        $this->assertSame(PackageDimensions::ORIGEM_ESTIMATIVA, $pacote->origem);
        $this->assertGreaterThanOrEqual(PackageEstimator::MIN_COMPRIMENTO, $pacote->comprimento);
        $this->assertGreaterThanOrEqual(PackageEstimator::MIN_LARGURA, $pacote->largura);
        $this->assertGreaterThanOrEqual(PackageEstimator::MIN_ALTURA, $pacote->altura);
    }

    public function test_estimativa_proporcional_nunca_ultrapassa_limites_dos_correios(): void
    {
        $pacote = $this->estimador([])->estimarPorPeso(200000);

        $this->assertLessThanOrEqual(PackageEstimator::MAX_LADO, max($pacote->comprimento, $pacote->largura, $pacote->altura));
        $this->assertLessThanOrEqual(PackageEstimator::MAX_SOMA_LADOS, $pacote->comprimento + $pacote->largura + $pacote->altura);
        $this->assertTrue($pacote->excedeCapacidade);
    }

    public function test_peso_zero_vira_um_grama(): void
    {
        $this->assertSame(1, $this->estimador()->estimarPorPeso(0)->pesoGramas);
    }

    public function test_pacote_sobrevive_ida_e_volta_por_array(): void
    {
        $original = $this->estimador()->estimarPorPeso(1500);

        $this->assertEquals($original->toArray(), PackageDimensions::fromArray($original->toArray())->toArray());
    }

    public function test_caixa_antiga_fixa_tinha_peso_cubico_de_8_75_kg(): void
    {
        // Regressão documentada: a caixa 54×36×27 que estava chumbada no código.
        $antiga = PackageDimensions::manual(54, 36, 27, 300);

        $this->assertSame(8.75, $antiga->pesoCubicoKg());
    }
}