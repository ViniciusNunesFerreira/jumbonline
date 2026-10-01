<?php

namespace App\Services\Shipping;

use App\Models\ShippingBox;
use App\Settings\ShippingSetting;
use Illuminate\Support\Facades\Log;

/**
 * Estima a embalagem de um pedido a partir do peso total, sem exigir que o
 * catálogo tenha dimensões cadastradas por produto.
 *
 * 1. Volume estimado (cm³) = peso (g) ÷ densidade aparente (g/cm³),
 *    acrescido da margem de embalagem/proteção.
 * 2. Encaixa o pedido na MENOR caixa ativa cujo volume interno comporte o
 *    volume estimado (e cujo peso máximo, quando informado, suporte o peso).
 * 3. Se nenhuma caixa comportar, usa a maior caixa cadastrada e sinaliza
 *    excedeCapacidade. Se não houver caixa cadastrada (ou a tabela ainda não
 *    existir), deriva arestas proporcionais (4:3:2) pela raiz cúbica do
 *    volume, respeitando os limites mínimos/máximos dos Correios.
 *
 * Nunca lança exceção por falta de configuração: este serviço é chamado no
 * checkout, e lógica auxiliar jamais pode interromper o fluxo de compra.
 * Settings ausentes caem nos defaults; tabela ausente cai na estimativa
 * proporcional.
 */
class PackageEstimator
{
    public const DEFAULT_DENSITY = 0.22;

    public const DEFAULT_MARGIN_PERCENT = 12;

    /** Limites do formato "caixa/pacote" dos Correios, em cm. */
    public const MIN_COMPRIMENTO = 16;

    public const MIN_LARGURA = 11;

    public const MIN_ALTURA = 2;

    public const MAX_LADO = 100;

    public const MAX_SOMA_LADOS = 200;

    /** Proporção das arestas na estimativa sem caixa (comprimento:largura:altura). */
    private const PROPORCAO = [4, 3, 2];

    private ?float $density;

    private ?int $marginPercent;

    /** @var array<int, array{id:?int, nome:string, comprimento:int, largura:int, altura:int, peso_max_g:?int, volume:int}>|null */
    private ?array $boxes = null;

    /**
     * Todos os parâmetros são opcionais: quando omitidos são resolvidos de
     * ShippingSetting e da tabela shipping_boxes. Injetá-los permite testar
     * o algoritmo sem banco, e simular parâmetros ainda não salvos na tela
     * de Embalagens.
     *
     * @param  iterable<ShippingBox|array>|null  $boxes
     */
    public function __construct(?float $density = null, ?int $marginPercent = null, ?iterable $boxes = null)
    {
        $this->density = $density;
        $this->marginPercent = $marginPercent;

        if ($boxes !== null) {
            $this->boxes = $this->normalizarCaixas($boxes);
        }
    }

    public function estimarPorPeso(int|float $pesoGramas): PackageDimensions
    {
        $peso = max(1, (int) ceil($pesoGramas));
        $volume = $this->volumeEstimado($peso);

        $caixas = $this->caixas();

        if (! empty($caixas)) {
            foreach ($caixas as $caixa) {
                $suportaPeso = $caixa['peso_max_g'] === null || $caixa['peso_max_g'] >= $peso;

                if ($caixa['volume'] >= $volume && $suportaPeso) {
                    return $this->pacoteDaCaixa($caixa, $peso, $volume, false);
                }
            }

            return $this->pacoteDaCaixa(end($caixas), $peso, $volume, true);
        }

        return $this->pacoteProporcional($peso, $volume);
    }

    /**
     * Volume estimado dos itens (cm³), já com a margem de embalagem.
     */
    public function volumeEstimado(int|float $pesoGramas): float
    {
        $peso = max(1, (float) $pesoGramas);

        return ($peso / $this->density()) * (1 + $this->marginPercent() / 100);
    }

    /**
     * Peso aproximado (g) que cabe num volume interno, pela densidade
     * configurada — usado para informar a "capacidade" de cada caixa na tela.
     */
    public function capacidadeEstimadaGramas(int $volumeCm3): int
    {
        return (int) floor(($volumeCm3 * $this->density()) / (1 + $this->marginPercent() / 100));
    }

    public function density(): float
    {
        if ($this->density === null) {
            $this->carregarSettings();
        }

        return $this->density;
    }

    public function marginPercent(): int
    {
        if ($this->marginPercent === null) {
            $this->carregarSettings();
        }

        return $this->marginPercent;
    }

    private function carregarSettings(): void
    {
        $density = self::DEFAULT_DENSITY;
        $margin = self::DEFAULT_MARGIN_PERCENT;

        try {
            $settings = app(ShippingSetting::class);
            $density = (float) $settings->estimated_density;
            $margin = (int) $settings->packaging_margin_percent;
        } catch (\Throwable $e) {
            Log::warning('[PackageEstimator] ShippingSetting indisponível — usando defaults.', ['error' => $e->getMessage()]);
        }

        $this->density ??= $density > 0 ? $density : self::DEFAULT_DENSITY;
        $this->marginPercent ??= max(0, $margin);
    }

    /**
     * Caixas ativas, ordenadas da menor para a maior (por volume).
     */
    private function caixas(): array
    {
        if ($this->boxes !== null) {
            return $this->boxes;
        }

        try {
            $this->boxes = $this->normalizarCaixas(ShippingBox::query()->active()->get());
        } catch (\Throwable $e) {
            Log::warning('[PackageEstimator] Tabela shipping_boxes indisponível — usando estimativa proporcional.', ['error' => $e->getMessage()]);
            $this->boxes = [];
        }

        return $this->boxes;
    }

    private function normalizarCaixas(iterable $boxes): array
    {
        $lista = [];

        foreach ($boxes as $box) {
            $dados = $box instanceof ShippingBox ? $box->only(['id', 'name', 'length_cm', 'width_cm', 'height_cm', 'max_weight_g']) : (array) $box;

            $comprimento = (int) ($dados['length_cm'] ?? 0);
            $largura = (int) ($dados['width_cm'] ?? 0);
            $altura = (int) ($dados['height_cm'] ?? 0);

            if ($comprimento <= 0 || $largura <= 0 || $altura <= 0) {
                continue;
            }

            $lista[] = [
                'id' => isset($dados['id']) ? (int) $dados['id'] : null,
                'nome' => (string) ($dados['name'] ?? 'Caixa'),
                'comprimento' => $comprimento,
                'largura' => $largura,
                'altura' => $altura,
                'peso_max_g' => isset($dados['max_weight_g']) && $dados['max_weight_g'] !== null ? (int) $dados['max_weight_g'] : null,
                'volume' => $comprimento * $largura * $altura,
            ];
        }

        usort($lista, fn ($a, $b) => $a['volume'] <=> $b['volume']);

        return $lista;
    }

    private function pacoteDaCaixa(array $caixa, int $peso, float $volume, bool $excede): PackageDimensions
    {
        return new PackageDimensions(
            comprimento: max(self::MIN_COMPRIMENTO, $caixa['comprimento']),
            largura: max(self::MIN_LARGURA, $caixa['largura']),
            altura: max(self::MIN_ALTURA, $caixa['altura']),
            pesoGramas: $peso,
            origem: PackageDimensions::ORIGEM_CAIXA,
            volumeEstimadoCm3: $volume,
            caixaId: $caixa['id'],
            caixaNome: $caixa['nome'],
            excedeCapacidade: $excede,
        );
    }

    private function pacoteProporcional(int $peso, float $volume): PackageDimensions
    {
        [$pc, $pl, $pa] = self::PROPORCAO;

        $k = ($volume / ($pc * $pl * $pa)) ** (1 / 3);

        // Limite dos Correios: soma das arestas ≤ 200 cm e nenhuma acima de 100 cm.
        $kMaximo = min(self::MAX_SOMA_LADOS / ($pc + $pl + $pa), self::MAX_LADO / $pc);
        $excede = $k > $kMaximo;
        $k = min($k, $kMaximo);

        $comprimento = (int) min(self::MAX_LADO, max(self::MIN_COMPRIMENTO, (int) ceil($pc * $k)));
        $largura = (int) min(self::MAX_LADO, max(self::MIN_LARGURA, (int) ceil($pl * $k)));
        $altura = (int) min(self::MAX_LADO, max(self::MIN_ALTURA, (int) ceil($pa * $k)));

        // O arredondamento para cima pode estourar a soma máxima em 1–2 cm.
        while ($comprimento + $largura + $altura > self::MAX_SOMA_LADOS) {
            if ($comprimento >= $largura && $comprimento > self::MIN_COMPRIMENTO) {
                $comprimento--;
            } elseif ($largura > self::MIN_LARGURA) {
                $largura--;
            } else {
                $altura--;
            }
        }

        return new PackageDimensions(
            comprimento: $comprimento,
            largura: $largura,
            altura: $altura,
            pesoGramas: $peso,
            origem: PackageDimensions::ORIGEM_ESTIMATIVA,
            volumeEstimadoCm3: $volume,
            excedeCapacidade: $excede,
        );
    }
}