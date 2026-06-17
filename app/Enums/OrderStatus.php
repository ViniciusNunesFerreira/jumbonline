<?php

namespace App\Enums;

enum OrderStatus
{
    case DRAFT;
    case PENDING;
    case OPEN; // payment has been made and the order is processing
    case COMPLETED;
    case ARCHIVED; // order completed and archived
    case CANCELLED; // order has been cancelled

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => '#fbbf24', // amber-400
            self::OPEN => '#60a5fa', // blue-400
            self::PENDING => '#60a5fa',
            self::COMPLETED => '#53a653',
            self::ARCHIVED, self::CANCELLED => '#94a3b8', // slate-400
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Rascunho'),
            self::OPEN => __('Open'),
            self::PENDING => __('Pendente'),
            self::COMPLETED => __('Completado'),
            self::ARCHIVED => __('Arquivado'),
            self::CANCELLED => __('Cancelado'),
        };
    }
}
