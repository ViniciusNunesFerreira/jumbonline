<?php

namespace App\Enums;

enum OrderStatus
{
    case DRAFT;
    case OPEN; // payment has been made and the order is processing
    case ARCHIVED; // order completed and archived
    case CANCELLED; // order has been cancelled

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => '#fbbf24', // amber-400
            self::OPEN => '#60a5fa', // blue-400
            self::ARCHIVED, self::CANCELLED => '#94a3b8', // slate-400
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::OPEN => __('Open'),
            self::ARCHIVED => __('Archived'),
            self::CANCELLED => __('Cancelled'),
        };
    }
}
