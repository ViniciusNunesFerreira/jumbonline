<?php

namespace App\Http\Livewire\Employee\Financial;

use App\Models\CashSession;
use App\Services\FinancialMetricsService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class CashReconciliation extends Component
{
    use WithPagination;

    public string $from = '';

    public string $to = '';

    protected $queryString = ['from', 'to'];

    public function mount()
    {
        $this->from = now()->startOfMonth()->toDateString();
        $this->to = now()->toDateString();
    }

    public function updatingFrom()
    {
        $this->resetPage();
    }

    public function updatingTo()
    {
        $this->resetPage();
    }

    protected function period(): array
    {
        return [Carbon::parse($this->from)->startOfDay(), Carbon::parse($this->to)->endOfDay()];
    }

    public function getSessionsProperty()
    {
        [$from, $to] = $this->period();

        return CashSession::query()
            ->where('status', 'closed')
            ->whereBetween('closed_at', [$from, $to])
            ->with('employee:id,name')
            ->latest('closed_at')
            ->paginate(15);
    }

    public function getSiteRevenueProperty()
    {
        [$from, $to] = $this->period();

        return app(FinancialMetricsService::class)->revenueByChannel($from, $to)['site'];
    }

    public function getPdvTotalsProperty()
    {
        [$from, $to] = $this->period();

        $sessions = CashSession::query()
            ->where('status', 'closed')
            ->whereBetween('closed_at', [$from, $to])
            ->get(['calculated_balance', 'closing_balance', 'difference']);

        return [
            'calculated' => (float) $sessions->sum('calculated_balance'),
            'closing' => (float) $sessions->sum('closing_balance'),
            'difference' => (float) $sessions->sum('difference'),
            'sessions_with_diff' => $sessions->where('difference', '!=', 0)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.employee.financial.cash-reconciliation', [
            'sessions' => $this->sessions,
            'pdvTotals' => $this->pdvTotals,
            'siteRevenue' => $this->siteRevenue,
        ])->layout('layouts.admin');
    }
}