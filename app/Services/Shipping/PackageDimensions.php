<?php

namespace App\Services\Shipping;

/**
 * Embalagem (dimensões externas em cm + peso em gramas) a ser informada aos
 * Correios — na cotação de frete ou na pré-postagem oficial.
 *
 * Imutável. Livewire 2 não serializa objetos em propriedades públicas, por
 * isso toArray()/fromArray() permitem trafegar o pacote como array entre
 * requisições sem perder nenhum campo.
 */
final class PackageDimensions
{
    public const ORIGEM_CAIXA = 'caixa';

    public const ORIGEM_ESTIMATIVA = 'estimativa';

    public const ORIGEM_MANUAL = 'manual';

    public function __construct(
        public readonly int $comprimento,
        public readonly int $largura,
        public readonly int $altura,
        public readonly int $pesoGramas,
        public readonly string $origem,
        public readonly float $volumeEstimadoCm3 = 0.0,
        public readonly ?int $caixaId = null,
        public readonly ?string $caixaNome = null,
        public readonly bool $excedeCapacidade = false,
    ) {
    }

    /**
     * Pacote informado pelo atendente após medir a caixa física real.
     */
    public static function manual(int $comprimento, int $largura, int $altura, int $pesoGramas, ?int $caixaId = null, ?string $caixaNome = null): self
    {
        return new self(
            comprimento: $comprimento,
            largura: $largura,
            altura: $altura,
            pesoGramas: max(1, $pesoGramas),
            origem: self::ORIGEM_MANUAL,
            volumeEstimadoCm3: (float) ($comprimento * $largura * $altura),
            caixaId: $caixaId,
            caixaNome: $caixaNome,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            comprimento: (int) ($data['comprimento'] ?? 0),
            largura: (int) ($data['largura'] ?? 0),
            altura: (int) ($data['altura'] ?? 0),
            pesoGramas: max(1, (int) ($data['peso_gramas'] ?? 1)),
            origem: (string) ($data['origem'] ?? self::ORIGEM_MANUAL),
            volumeEstimadoCm3: (float) ($data['volume_estimado_cm3'] ?? 0),
            caixaId: isset($data['caixa_id']) && $data['caixa_id'] !== '' ? (int) $data['caixa_id'] : null,
            caixaNome: $data['caixa_nome'] ?? null,
            excedeCapacidade: (bool) ($data['excede_capacidade'] ?? false),
        );
    }

    public function toArray(): array
    {
        return [
            'comprimento' => $this->comprimento,
            'largura' => $this->largura,
            'altura' => $this->altura,
            'peso_gramas' => $this->pesoGramas,
            'origem' => $this->origem,
            'volume_estimado_cm3' => round($this->volumeEstimadoCm3, 1),
            'caixa_id' => $this->caixaId,
            'caixa_nome' => $this->caixaNome,
            'excede_capacidade' => $this->excedeCapacidade,
        ];
    }

    /**
     * Volume externo da embalagem em cm³.
     */
    public function volumeCm3(): int
    {
        return $this->comprimento * $this->largura * $this->altura;
    }

    /**
     * Peso cúbico dos Correios (C × L × A / 6000), em kg.
     */
    public function pesoCubicoKg(): float
    {
        return round($this->volumeCm3() / 6000, 2);
    }

    public function pesoKg(): float
    {
        return round($this->pesoGramas / 1000, 3);
    }

    /**
     * Ocupação do volume estimado dos itens dentro da embalagem (0–100+).
     */
    public function ocupacaoPercentual(): float
    {
        $volume = $this->volumeCm3();

        if ($volume <= 0 || $this->volumeEstimadoCm3 <= 0) {
            return 0.0;
        }

        return round(($this->volumeEstimadoCm3 / $volume) * 100, 1);
    }

    public function descricao(): string
    {
        $medidas = "{$this->comprimento} × {$this->largura} × {$this->altura} cm";

        return match ($this->origem) {
            self::ORIGEM_CAIXA => ($this->caixaNome ?? 'Caixa') . " ({$medidas})",
            self::ORIGEM_MANUAL => "Medida no balcão ({$medidas})",
            default => "Estimativa proporcional ({$medidas})",
        };
    }

    /**
     * Parâmetros de dimensão no formato da API de Preço dos Correios.
     */
    public function toPrecoQuery(): array
    {
        return [
            'psObjeto' => $this->pesoGramas,
            'tpObjeto' => 2,
            'comprimento' => $this->comprimento,
            'largura' => $this->largura,
            'altura' => $this->altura,
        ];
    }
}