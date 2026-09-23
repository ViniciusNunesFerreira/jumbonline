<?php

namespace App\Enums;

enum StockMovementType
{
    case VENDA;
    case CANCELAMENTO;
    case DEVOLUCAO;
    case ENTRADA;
    case PERDA;
    case AJUSTE;

    public function label(): string
    {
        return match ($this) {
            self::VENDA => __('Venda'),
            self::CANCELAMENTO => __('Cancelamento'),
            self::DEVOLUCAO => __('Devolução'),
            self::ENTRADA => __('Entrada manual'),
            self::PERDA => __('Perda/avaria'),
            self::AJUSTE => __('Ajuste de contagem'),
        };
    }

    public function badgeType(): string
    {
        return match ($this) {
            self::VENDA => 'default',
            self::CANCELAMENTO, self::DEVOLUCAO, self::ENTRADA => 'success',
            self::PERDA => 'danger',
            self::AJUSTE => 'warning',
        };
    }
}