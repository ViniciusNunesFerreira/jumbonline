<?php

namespace App\Enums;

/**
 * O campo existe desde a Fase 1 (schema), mas o motor que gera
 * automaticamente a próxima ocorrência é construído só na Fase 3 —
 * ver feature-roadmap.md. Por enquanto, escolher aqui não dispara nada.
 */
enum ExpenseRecurrence: string
{
    case NONE = 'none';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public function label(): string
    {
        return match ($this) {
            self::NONE => 'Não recorrente',
            self::WEEKLY => 'Semanal',
            self::MONTHLY => 'Mensal',
            self::YEARLY => 'Anual',
        };
    }
}