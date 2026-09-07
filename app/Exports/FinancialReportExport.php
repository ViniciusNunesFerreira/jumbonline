<?php

namespace App\Exports;

use App\Services\FinancialMetricsService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinancialReportExport implements WithMultipleSheets
{
    protected array $metrics;

    public function __construct(Carbon $from, Carbon $to)
    {
        $service = app(FinancialMetricsService::class);

        $this->metrics = [
            'from' => $from->format('d/m/Y'),
            'to' => $to->format('d/m/Y'),
            'net_revenue' => $service->netRevenue($from, $to),
            'paid_orders_count' => $service->paidOrdersCount($from, $to),
            'by_channel' => $service->revenueByChannel($from, $to),
            'by_payment_method' => $service->revenueByPaymentMethod($from, $to),
            'margin' => $service->grossMargin($from, $to),
            'abc_curve' => $service->abcCurve($from, $to),
            'prison_ranking' => $service->prisonUnitRanking($from, $to, 50),
        ];
    }

    public function sheets(): array
    {
        return [
            'Resumo' => new Sheets\FinancialSummarySheet($this->metrics),
            'Metodo Pagamento' => new Sheets\PaymentMethodSheet($this->metrics),
            'Curva ABC' => new Sheets\AbcCurveSheet($this->metrics),
            'Unidades Prisionais' => new Sheets\PrisonUnitRankingSheet($this->metrics),
        ];
    }
}