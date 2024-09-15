<?php

namespace App\Enums;

enum ShippingServices: string
{
    case SEDEX_CONTRATO_AG = '03220';
    case PAC_CONTRATO_AG = '03298';
    case APIPRECOS = '38202';
    case APIPRAZOS = '38210';

    public function label(): string
    {
        return match ($this) {
            self::SEDEX_CONTRATO_AG => '03220',
            self::PAC_CONTRATO_AG => '03298',
            self::APIPRECOS => '38202',
            self::APIPRAZOS => '38210',
        };
    }

}