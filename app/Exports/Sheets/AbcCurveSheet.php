<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AbcCurveSheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(protected array $metrics)
    {
    }

    public function headings(): array
    {
        return ['Produto', 'Receita', 'Quantidade', '% Acumulado', 'Classe'];
    }

    public function array(): array
    {
        return array_map(fn($row) => [
            $row['name'], $row['revenue'], $row['quantity'], $row['cumulative_percent'], $row['class'],
        ], $this->metrics['abc_curve']);
    }

    public function title(): string
    {
        return 'Curva ABC';
    }
}