<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OrderSetting extends Settings
{
    /**
     * Dias sem pré-postagem/envio criado, após a confirmação do pagamento,
     * para um pedido pago ser sinalizado como "parado" na lista de Pedidos
     * (e futuramente na seção operacional do Dashboard).
     */
    public int $stalled_order_days_threshold;

    public static function group(): string
    {
        return 'order';
    }
}