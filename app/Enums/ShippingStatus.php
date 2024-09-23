<?php

namespace App\Enums;

enum ShippingStatus: string
{
    case UNSHIPPED = 'unshipped';
    case PARTIALLY_SHIPPED = 'partially_shipped';
    case SHIPPED = 'shipped';

    public function label(): string
    {
        return match ($this) {
            self::UNSHIPPED => __('Envio Pendente'),
            self::PARTIALLY_SHIPPED => __('Parcialmente Enviado'),
            self::SHIPPED => __('Enviado'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNSHIPPED, self::PARTIALLY_SHIPPED => '#fbbf24', // amber-400
            self::SHIPPED => '#60a5fa', // blue-400
        };
    }
}
