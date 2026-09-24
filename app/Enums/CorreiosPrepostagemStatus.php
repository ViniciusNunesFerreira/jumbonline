<?php

namespace App\Enums;

enum CorreiosPrepostagemStatus: int
{
    case PREATENDIDO = 1;
    case PREPOSTADO = 2;
    case POSTADO = 3;
    case EXPIRADO = 4;
    case CANCELADO = 5;
    case ESTORNADO = 6;
    case PENDENTE = 7;

    public function label(): string
    {
        return match ($this) {
            self::PREATENDIDO => __('Pré-atendido'),
            self::PREPOSTADO => __('Pré-postado'),
            self::POSTADO => __('Postado'),
            self::EXPIRADO => __('Expirado'),
            self::CANCELADO => __('Cancelado'),
            self::ESTORNADO => __('Estornado'),
            self::PENDENTE => __('Processando'),
        };
    }

    public function badgeType(): string
    {
        return match ($this) {
            self::POSTADO => 'success',
            self::PREPOSTADO, self::PREATENDIDO => 'default',
            self::PENDENTE => 'warning',
            self::EXPIRADO, self::CANCELADO, self::ESTORNADO => 'danger',
        };
    }
}