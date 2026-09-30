<?php

// app/Models/Expense.php

namespace App\Models;

use App\Enums\ExpensePaymentMethod;
use App\Enums\ExpenseRecurrence;
use App\Enums\ExpenseStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Conta a pagar. O status (Pendente/Pago/Atrasado) nunca é uma coluna —
 * é sempre computado a partir de due_date/payment_date por status(), abaixo,
 * no mesmo espírito de Discount::status(). Isso evita que status e datas
 * fiquem dessincronizados por um update parcial em algum lugar do sistema.
 */
class Expense extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'payee_id',
        'expense_category_id',
        'description',
        'amount',
        'due_date',
        'payment_date',
        'payment_method',
        'recurrence_type',
        'recurrence_parent_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'due_date' => 'date',
        'payment_date' => 'date',
        'payment_method' => ExpensePaymentMethod::class,
        'recurrence_type' => ExpenseRecurrence::class,
    ];

    protected $attributes = [
        'recurrence_type' => 'none',
    ];

    public function payee(): BelongsTo
    {
        return $this->belongsTo(Payee::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * A despesa "modelo" que originou esta ocorrência, quando gerada
     * automaticamente pelo motor de recorrência (Fase 3).
     */
    public function recurrenceParent(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'recurrence_parent_id');
    }

    /**
     * Ocorrências já geradas a partir desta despesa (quando ela é o modelo
     * de uma recorrência). Vazio para despesas avulsas ou para ocorrências
     * já geradas (que não geram novas ocorrências por si mesmas).
     */
    public function recurrenceChildren(): HasMany
    {
        return $this->hasMany(Expense::class, 'recurrence_parent_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('boleto')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);
    }

    /**
     * Contas não pagas vencendo entre duas datas (inclusive) — usado tanto
     * no painel operacional do Dashboard geral quanto no bloco de fluxo de
     * caixa do Financeiro, pra não duplicar a mesma consulta nos dois.
     */
    public function scopeDueBetween(\Illuminate\Database\Eloquent\Builder $query, $from, $to): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->whereNull('payment_date')
            ->whereDate('due_date', '>=', $from)
            ->whereDate('due_date', '<=', $to);
    }

    /**
     * Contas não pagas com vencimento já passado.
     */
    public function scopeOverdue(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->whereNull('payment_date')
            ->whereDate('due_date', '<', today());
    }

    /**
     * Contas efetivamente pagas dentro do período (para o fluxo de caixa,
     * que é sempre por regime de caixa — o mesmo critério já usado na
     * receita: dinheiro que de fato saiu, não o que só venceu).
     */
    public function scopePaidBetween(\Illuminate\Database\Eloquent\Builder $query, $from, $to): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->whereNotNull('payment_date')
            ->whereDate('payment_date', '>=', $from)
            ->whereDate('payment_date', '<=', $to);
    }

    /**
     * Data da próxima ocorrência de uma despesa recorrente, a partir da
     * data de vencimento atual. Método puro (sem consulta ao banco) para
     * ser testável isoladamente — ver GenerateRecurringExpenses, o comando
     * que efetivamente usa isso pra criar a próxima ocorrência.
     *
     * addMonthNoOverflow() é proposital: vencimento dia 31 deve virar dia
     * 28/29 em fevereiro, nunca "vazar" pro dia 2-3 de março.
     */
    public static function nextDueDate(\Illuminate\Support\Carbon $currentDueDate, ExpenseRecurrence $recurrenceType): ?\Illuminate\Support\Carbon
    {
        return match ($recurrenceType) {
            ExpenseRecurrence::WEEKLY => $currentDueDate->copy()->addWeek(),
            ExpenseRecurrence::MONTHLY => $currentDueDate->copy()->addMonthNoOverflow(),
            ExpenseRecurrence::YEARLY => $currentDueDate->copy()->addYear(),
            ExpenseRecurrence::NONE => null,
        };
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->payment_date) {
                    return ExpenseStatus::PAID;
                }

                // Vencida só a partir do dia seguinte ao vencimento — no
                // próprio dia do vencimento a conta ainda é Pendente, não
                // Atrasada. due_date é um cast 'date' (meia-noite), então
                // comparar com now() classificaria como atrasada a partir
                // da primeira hora do próprio dia de vencimento.
                if ($this->due_date->isBefore(today())) {
                    return ExpenseStatus::OVERDUE;
                }

                return ExpenseStatus::PENDING;
            }
        );
    }
}