<?php

namespace App\Enums;

enum ProductType
{
    case SIMPLES;
    case KIT;


    public function label(): string
    {
        return match ($this) {
            self::SIMPLES => trans('Simples'),
            self::KIT => trans('Kit Jumbo'),
        };
    }
}