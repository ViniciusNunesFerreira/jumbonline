<?php

namespace App\Http\Livewire\Employee\Financial;

use App\Exports\FinancialReportExport;
use App\Services\FinancialMetricsService;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class FinancialDashboard extends Component
{
    public string $from = '';

    public string $to = '';

    public string $preset = 'month';

    protected $queryString = ['from', 'to', 'preset'];

    public function mount()
    {
        $this->applyPreset('month');
    }

    public function applyPreset(string $preset)
    {
        $this->preset = $preset;

        [$this->from, $this->to] = match ($preset) {
            'today' => [now()->toDateString(), now()->toDateString()],
            '7d' => [now()->subDays(6)->toDateString(), now()->toDateString()],
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth()->toDateString(), now()->subMonthNoOverflow()->endOfMonth()->toDateString()],
            default => [now()->startOfMonth()->toDateString(), now()->toDateString()],
        };
    }

    protected function period(): array
    {
        return [
            Carbon::parse($this->from)->startOfDay(),
            Carbon::parse($this->to)->endOfDay(),
        ];
    }

    public function getMetricsProperty()
    {
        [$from, $to] = $this->period();

        $service = app(FinancialMetricsService::class);

        return [
            'net_revenue' => $service->netRevenue($from, $to),
            'paid_orders_count' => $service->paidOrdersCount($from, $to),
            'by_channel' => $service->revenueByChannel($from, $to),
            'by_payment_method' => $service->revenueByPaymentMethod($from, $to),
            'margin' => $service->grossMargin($from, $to),
            'abc_top' => array_slice($service->abcCurve($from, $to), 0, 5),
            'prison_ranking_top' => $service->prisonUnitRanking($from, $to, 5),
        ];
    }

    public function exportPdf()
    {
        [$from, $to] = $this->period();
        $service = app(FinancialMetricsService::class);

        $data = [
            'from' => $from,
            'to' => $to,
            'metrics' => [
                'net_revenue' => $service->netRevenue($from, $to),
                'paid_orders_count' => $service->paidOrdersCount($from, $to),
                'by_channel' => $service->revenueByChannel($from, $to),
                'by_payment_method' => $service->revenueByPaymentMethod($from, $to),
                'margin' => $service->grossMargin($from, $to),
                'abc_top' => $service->abcCurve($from, $to),
                'prison_ranking_top' => $service->prisonUnitRanking($from, $to, 50),
            ],
        ];

        $fileName = "relatorio-financeiro-{$this->from}-a-{$this->to}.pdf";

        return response()->streamDownload(function () use ($data) {
            echo \Pdf::loadView('employee.financial.exports.report-pdf', $data)->setPaper('a4')->output();
        }, $fileName);
    }

    public function exportXls()
    {
        [$from, $to] = $this->period();

        $fileName = "relatorio-financeiro-{$this->from}-a-{$this->to}.xlsx";

        return Excel::download(new FinancialReportExport($from, $to), $fileName);
    }

    public function render()
    {
        return view('livewire.employee.financial.financial-dashboard', [
            'metrics' => $this->metrics,
        ])->layout('layouts.admin');
    }
}