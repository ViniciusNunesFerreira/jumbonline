<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class InventorySetting extends Settings
{
    public int $default_low_stock_threshold;

    public static function group(): string
    {
        return 'inventory';
    }
}