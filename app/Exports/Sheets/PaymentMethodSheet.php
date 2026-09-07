<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentMethodSheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(protected array $metrics)
    {
    }

    public function headings(): array
    {
        return ['Método', 'Total'];
    }

    public function array(): array
    {
        return array_map(fn($row) => [$row['method'], $row['total']], $this->metrics['by_payment_method']);
    }

    public function title(): string
    {
        return 'Metodo Pagamento';
    }
}