<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Favorecido de uma conta a pagar. Pode ser um Employee já cadastrado
 * (folha de pagamento) ou um nome livre (fornecedor, concessionária) — ver
 * decisão registrada em feature-roadmap.md. O nome é sempre copiado para
 * cá na criação, mesmo quando vinculado a um funcionário, para que o
 * histórico de despesas continue legível se o funcionário for excluído
 * depois (employee_id vira null, name permanece).
 */
class Payee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'employee_id',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}