<?php

namespace App\Enums;

enum ExpensePaymentMethod: string
{
    case PIX = 'pix';
    case DINHEIRO = 'dinheiro';
    case CARTAO = 'cartao';
    case TRANSFERENCIA = 'transferencia';
    case BOLETO = 'boleto';
    case OUTRO = 'outro';

    public function label(): string
    {
        return match ($this) {
            self::PIX => 'Pix',
            self::DINHEIRO => 'Dinheiro',
            self::CARTAO => 'Cartão',
            self::TRANSFERENCIA => 'Transferência',
            self::BOLETO => 'Boleto',
            self::OUTRO => 'Outro',
        };
    }
}