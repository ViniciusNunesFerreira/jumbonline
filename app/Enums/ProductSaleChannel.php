<?php

namespace App\Enums;

enum ProductSaleChannel
{
    case SITE;
    case BALCAO;
    case AMBOS;


    public function label(): string
    {
        return match ($this) {
            self::SITE => trans('Apenas Site'),
            self::BALCAO => trans('Apenas Balcão (PDV)'),
            self::AMBOS => trans('Ambos (Site e PDV)'),
        };
    }
}