<?php

namespace App\Enums;

enum InteractionChannel
{
    case WHATSAPP;
    case TELEFONE;
    case EMAIL;
    case PRESENCIAL;
    case SISTEMA;

    public function label(): string
    {
        return match ($this) {
            self::WHATSAPP => __('WhatsApp'),
            self::TELEFONE => __('Telefone'),
            self::EMAIL => __('E-mail'),
            self::PRESENCIAL => __('Presencial'),
            self::SISTEMA => __('Sistema'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::WHATSAPP => 'heroicon-o-chat-bubble-left-right',
            self::TELEFONE => 'heroicon-o-phone',
            self::EMAIL => 'heroicon-o-envelope',
            self::PRESENCIAL => 'heroicon-o-building-storefront',
            self::SISTEMA => 'heroicon-o-cog-6-tooth',
        };
    }
}