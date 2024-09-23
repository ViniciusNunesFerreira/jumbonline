<?php

namespace App\Enums;

enum ShippingCarrier: string
{
    case CORREIOS = 'correios';
    case OTHER = 'transportadora';

    public function label(): string
    {
        return match ($this) {
            self::CORREIOS => 'Correios',
            self::OTHER => 'Transportadora',
        };
    }
}
