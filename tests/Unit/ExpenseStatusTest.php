<?php

namespace Tests\Unit;

use App\Enums\ExpenseStatus;
use App\Models\Expense;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Cobre Expense::status(), sempre computado a partir de due_date/payment_date
 * (nunca uma coluna própria). Não acessa banco de dados: a despesa é
 * construída em memória com os atributos já preenchidos.
 */
class ExpenseStatusTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-09-29 15:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function makeExpense(array $attributes): Expense
    {
        $expense = new Expense();
        $expense->forceFill($attributes);

        return $expense;
    }

    public function test_expense_with_payment_date_is_paid_regardless_of_due_date(): void
    {
        $expense = $this->makeExpense([
            'due_date' => '2026-09-10',
            'payment_date' => '2026-09-29',
        ]);

        $this->assertSame(ExpenseStatus::PAID, $expense->status);
    }

    public function test_unpaid_expense_due_in_the_future_is_pending(): void
    {
        $expense = $this->makeExpense([
            'due_date' => '2026-10-05',
            'payment_date' => null,
        ]);

        $this->assertSame(ExpenseStatus::PENDING, $expense->status);
    }

    public function test_unpaid_expense_due_today_is_still_pending_not_overdue(): void
    {
        $expense = $this->makeExpense([
            'due_date' => '2026-09-29',
            'payment_date' => null,
        ]);

        $this->assertSame(ExpenseStatus::PENDING, $expense->status);
    }

    public function test_unpaid_expense_due_yesterday_is_overdue(): void
    {
        $expense = $this->makeExpense([
            'due_date' => '2026-09-28',
            'payment_date' => null,
        ]);

        $this->assertSame(ExpenseStatus::OVERDUE, $expense->status);
    }

    public function test_unpaid_expense_due_long_ago_is_overdue(): void
    {
        $expense = $this->makeExpense([
            'due_date' => '2026-01-01',
            'payment_date' => null,
        ]);

        $this->assertSame(ExpenseStatus::OVERDUE, $expense->status);
    }

    public function test_paid_expense_that_was_paid_late_is_still_paid(): void
    {
        // Pagou atrasado: o que importa pro status é ter sido paga, não a
        // pontualidade. O atraso fica registrado na diferença entre as datas,
        // não no status.
        $expense = $this->makeExpense([
            'due_date' => '2026-01-01',
            'payment_date' => '2026-02-01',
        ]);

        $this->assertSame(ExpenseStatus::PAID, $expense->status);
    }
}