<?php


namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CustomerSetting extends Settings
{
    /**
     * Número mínimo de pedidos pagos (customers.paid_orders_count) para o
     * cliente ganhar o selo de "Cliente frequente" na lista e no cadastro.
     */
    public int $frequent_customer_min_orders;

    public static function group(): string
    {
        return 'customer';
    }
}