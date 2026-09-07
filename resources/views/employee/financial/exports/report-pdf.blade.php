<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1e293b; }
        h1 { font-size: 18px; margin-bottom: 0; }
        h2 { font-size: 14px; margin-top: 24px; margin-bottom: 6px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { text-align: left; padding: 4px 6px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        th { background: #f1f5f9; }
        .text-right { text-align: right; }
        .muted { color: #64748b; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Relatório Financeiro — Jumbonline</h1>
    <p class="muted">Período: {{ $from->format('d/m/Y') }} a {{ $to->format('d/m/Y') }}</p>

    <h2>Resumo</h2>
    <table>
        <tr><td>Receita líquida</td><td class="text-right">R$ {{ number_format($metrics['net_revenue'], 2, ',', '.') }}</td></tr>
        <tr><td>Pedidos pagos</td><td class="text-right">{{ $metrics['paid_orders_count'] }}</td></tr>
        <tr><td>Receita site (Mercado Pago)</td><td class="text-right">R$ {{ number_format($metrics['by_channel']['site'], 2, ',', '.') }}</td></tr>
        <tr><td>Receita PDV (balcão)</td><td class="text-right">R$ {{ number_format($metrics['by_channel']['pdv'], 2, ',', '.') }}</td></tr>
        <tr><td>Margem bruta</td><td class="text-right">{{ $metrics['margin']['margin_percent'] }}%</td></tr>
        <tr><td>Lucro bruto</td><td class="text-right">R$ {{ number_format($metrics['margin']['profit'], 2, ',', '.') }}</td></tr>
    </table>

    <h2>Receita por método de pagamento</h2>
    <table>
        <thead><tr><th>Método</th><th class="text-right">Total</th></tr></thead>
        <tbody>
        @forelse($metrics['by_payment_method'] as $row)
            <tr><td>{{ $row['method'] }}</td><td class="text-right">R$ {{ number_format($row['total'], 2, ',', '.') }}</td></tr>
        @empty
            <tr><td colspan="2">Nenhum pagamento no período.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Curva ABC de produtos</h2>
    <table>
        <thead><tr><th>Produto</th><th class="text-right">Receita</th><th class="text-right">Qtd.</th><th class="text-right">% Acum.</th><th>Classe</th></tr></thead>
        <tbody>
        @forelse($metrics['abc_top'] as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td class="text-right">R$ {{ number_format($row['revenue'], 2, ',', '.') }}</td>
                <td class="text-right">{{ $row['quantity'] }}</td>
                <td class="text-right">{{ $row['cumulative_percent'] }}%</td>
                <td>{{ $row['class'] }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Nenhuma venda no período.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Unidades prisionais — maior volume</h2>
    <table>
        <thead><tr><th>Unidade</th><th class="text-right">Receita</th><th class="text-right">Pedidos</th></tr></thead>
        <tbody>
        @forelse($metrics['prison_ranking_top'] as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td class="text-right">R$ {{ number_format($row['revenue'], 2, ',', '.') }}</td>
                <td class="text-right">{{ $row['orders_count'] }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Nenhuma venda no período.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>