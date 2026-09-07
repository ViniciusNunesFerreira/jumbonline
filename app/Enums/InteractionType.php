<?php

namespace App\Enums;

enum InteractionType
{
    case NOTA_INTERNA;
    case CHAMADO_SUPORTE;
    case CONTATO_MANUAL;
    case CARRINHO_ABANDONADO;
    case RESET_SENHA;

    public function label(): string
    {
        return match ($this) {
            self::NOTA_INTERNA => __('Nota interna'),
            self::CHAMADO_SUPORTE => __('Chamado de suporte'),
            self::CONTATO_MANUAL => __('Contato manual'),
            self::CARRINHO_ABANDONADO => __('Carrinho abandonado'),
            self::RESET_SENHA => __('Redefinição de senha'),
        };
    }

    public function badgeType(): string
    {
        return match ($this) {
            self::NOTA_INTERNA => 'default',
            self::CHAMADO_SUPORTE => 'warning',
            self::CONTATO_MANUAL => 'primary',
            self::CARRINHO_ABANDONADO => 'danger',
            self::RESET_SENHA => 'warning',
        };
    }
}