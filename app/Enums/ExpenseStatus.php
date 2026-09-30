<?php

namespace App\Enums;

/**
 * Status da conta a pagar — sempre computado por Expense::status() a partir
 * de due_date e payment_date, nunca armazenado como coluna. Mesmo espírito
 * de Discount::status() (também computado, não persistido).
 */
enum ExpenseStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case OVERDUE = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::PAID => 'Pago',
            self::OVERDUE => 'Atrasado',
        };
    }

    /**
     * Paleta própria (não replica PaymentStatus::color()) porque aqui
     * Atrasado precisa alarmar visualmente e se distinguir de Pendente —
     * em PaymentStatus os dois compartilham a mesma cor (âmbar), o que
     * funciona lá mas esconderia o atraso numa tela de contas a pagar.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => '#f59e0b',
            self::PAID => '#22c55e',
            self::OVERDUE => '#ef4444',
        };
    }
}