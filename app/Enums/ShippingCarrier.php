<?php

namespace App\Enums;

enum ShippingCarrier: string
{
    case UPS = 'ups';
    case USPS = 'usps';
    case FEDEX = 'fedex';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::UPS => 'UPS',
            self::USPS => 'USPS',
            self::FEDEX => 'Fedex',
            self::OTHER => 'Other',
        };
    }
}
