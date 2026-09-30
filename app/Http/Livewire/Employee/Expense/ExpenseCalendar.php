<?php

namespace App\Http\Livewire\Employee\Expense;

use App\Models\Expense;
use Carbon\Carbon;
use Livewire\Component;

/**
 * Calendário de vencimentos (Fase 5 do módulo de Contas a Pagar). Grade de
 * mês em Blade/Alpine puro — decisão já registrada em feature-roadmap.md de
 * não adicionar nenhuma biblioteca de calendário só para isso.
 */
class ExpenseCalendar extends Component
{
    public Carbon $month;

    public function mount()
    {
        $this->month = today()->startOfMonth();
    }

    public function previousMonth()
    {
        $this->month = $this->month->copy()->subMonthNoOverflow()->startOfMonth();
    }

    public function nextMonth()
    {
        $this->month = $this->month->copy()->addMonthNoOverflow()->startOfMonth();
    }

    public function goToToday()
    {
        $this->month = today()->startOfMonth();
    }

    /**
     * Despesas do mês visível, agrupadas por dia ("Y-m-d") pra a view só
     * precisar indexar por dia ao montar cada célula da grade.
     */
    public function getExpensesByDayProperty()
    {
        return Expense::query()
            ->with('payee:id,name')
            ->whereBetween('due_date', [$this->month->copy()->startOfMonth(), $this->month->copy()->endOfMonth()])
            ->orderBy('due_date')
            ->get()
            ->groupBy(fn (Expense $expense) => $expense->due_date->format('Y-m-d'));
    }

    /**
     * Semanas do mês, cada uma com 7 dias — null nas posições antes do dia 1
     * ou depois do último dia, pra a grade sempre fechar em múltiplos de 7
     * (mesma técnica de qualquer calendário simples em grade).
     */
    public function getCalendarWeeksProperty()
    {
        $start = $this->month->copy()->startOfMonth();
        $daysInMonth = $start->daysInMonth;

        $days = array_fill(0, $start->dayOfWeek, null);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $days[] = $start->copy()->setDay($day);
        }

        while (count($days) % 7 !== 0) {
            $days[] = null;
        }

        return array_chunk($days, 7);
    }

    public function render()
    {
        return view('livewire.employee.expense.expense-calendar', [
            'expensesByDay' => $this->expensesByDay,
            'weeks' => $this->calendarWeeks,
        ])->layout('layouts.admin');
    }
}