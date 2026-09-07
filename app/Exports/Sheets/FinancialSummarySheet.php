<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FinancialSummarySheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(protected array $metrics)
    {
    }

    public function headings(): array
    {
        return ['Indicador', 'Valor'];
    }

    public function array(): array
    {
        $m = $this->metrics;

        return [
            ['Período', $m['from'] . ' a ' . $m['to']],
            ['Receita líquida', $m['net_revenue']],
            ['Pedidos pagos', $m['paid_orders_count']],
            ['Receita site (Mercado Pago)', $m['by_channel']['site']],
            ['Receita PDV (balcão)', $m['by_channel']['pdv']],
            ['Receita bruta (base margem)', $m['margin']['revenue']],
            ['Custo (base margem)', $m['margin']['cost']],
            ['Lucro bruto', $m['margin']['profit']],
            ['Margem (%)', $m['margin']['margin_percent']],
        ];
    }

    public function title(): string
    {
        return 'Resumo';
    }
}