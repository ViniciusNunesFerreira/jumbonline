<?php

namespace App\Enums;

enum PaymentStatus
{
    case AUTHORIZED;
    case EXPIRED;
    case OVERDUE;
    case PAID;
    case PENDING; // Payment has been made and is awaiting confirmation from webhooks or manual confirmation from the seller
    case PARTIALLY_REFUNDED;
    case REFUNDED;
    case UNPAID; // Order created but not paid

    public function color(): string
    {
        return match ($this) {
            self::PENDING, self::UNPAID, self::OVERDUE => '#fbbf24', // amber-400
            self::PARTIALLY_REFUNDED, self::REFUNDED, self::EXPIRED => '#94a3b8', // slate-400
            self::AUTHORIZED, self::PAID => '#60a5fa', // blue-400
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::AUTHORIZED => __('Authorized'),
            self::EXPIRED => __('Expired'),
            self::OVERDUE => __('Overdue'),
            self::PAID => __('Paid'),
            self::PENDING => __('Awaiting Confirmation'),
            self::PARTIALLY_REFUNDED => __('Partially Refunded'),
            self::REFUNDED => __('Refunded'),
            self::UNPAID => __('Unpaid'),
        };
    }
}
