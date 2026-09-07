<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PrisonUnitRankingSheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(protected array $metrics)
    {
    }

    public function headings(): array
    {
        return ['Unidade Prisional', 'Receita', 'Pedidos'];
    }

    public function array(): array
    {
        return array_map(fn($row) => [$row['name'], $row['revenue'], $row['orders_count']], $this->metrics['prison_ranking']);
    }

    public function title(): string
    {
        return 'Unidades Prisionais';
    }
}