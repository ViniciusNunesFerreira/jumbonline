<?php

namespace App\Console\Commands;

use App\Enums\ExpenseRecurrence;
use App\Models\Expense;
use Illuminate\Console\Command;

/**
*/
class GenerateRecurringExpenses extends Command
{
    /**
     * Gera a próxima ocorrência quando a atual vence dentro desse número de
     * dias — dá alguma folga pra revisar a conta antes do vencimento, em
     * vez de só criá-la no dia exato.
     */
    private const LEAD_DAYS = 5;

    protected $signature = 'expenses:generate-recurring {--dry-run}';

    protected $description = 'Gera automaticamente a próxima ocorrência de despesas recorrentes (aluguel, assinaturas etc.) cuja ocorrência atual está vencendo em breve';

    public function handle(): int
    {
        $roots = Expense::query()
            ->whereNull('recurrence_parent_id')
            ->where('recurrence_type', '!=', ExpenseRecurrence::NONE->value)
            ->get();

        if ($roots->isEmpty()) {
            $this->info('Nenhuma despesa recorrente cadastrada.');

            return self::SUCCESS;
        }

        $created = 0;
        $dryRun = (bool) $this->option('dry-run');

        foreach ($roots as $root) {
            $latest = $root->recurrenceChildren()->orderByDesc('due_date')->first() ?? $root;

            if ($latest->recurrence_type === ExpenseRecurrence::NONE) {
                continue;
            }

            if ($latest->due_date->isAfter(today()->addDays(self::LEAD_DAYS))) {
                continue;
            }

            $nextDueDate = Expense::nextDueDate($latest->due_date, $latest->recurrence_type);

            if ($nextDueDate === null) {
                continue;
            }

            $alreadyExists = $root->recurrenceChildren()
                ->whereDate('due_date', $nextDueDate)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $this->line(sprintf(
                '%s "%s" — próxima ocorrência em %s (R$ %s)',
                $dryRun ? '[dry-run]' : 'Gerando:',
                $latest->description,
                $nextDueDate->format('d/m/Y'),
                number_format($latest->amount, 2, ',', '.')
            ));

            if (! $dryRun) {
                Expense::create([
                    'payee_id' => $latest->payee_id,
                    'expense_category_id' => $latest->expense_category_id,
                    'description' => $latest->description,
                    'amount' => $latest->amount,
                    'due_date' => $nextDueDate,
                    'payment_date' => null,
                    'payment_method' => null,
                    'recurrence_type' => $latest->recurrence_type->value,
                    'recurrence_parent_id' => $root->id,
                    'notes' => $latest->notes,
                ]);
            }

            $created++;
        }

        if ($created === 0) {
            $this->info('Nenhuma ocorrência precisou ser gerada agora.');

            return self::SUCCESS;
        }

        $this->info(($dryRun ? "{$created} ocorrência(s) seriam geradas (--dry-run, nada foi criado)." : "{$created} ocorrência(s) gerada(s)."));

        return self::SUCCESS;
    }
}