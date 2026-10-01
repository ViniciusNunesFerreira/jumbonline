<?php

namespace App\Console\Commands;

use App\Enums\ShippingCarrier;
use App\Models\PrisonUnit;
use App\Models\Shipment;
use App\Models\ShippingMethod;
use App\Services\Shipping\PackageEstimator;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Análise comercial do frete — SOMENTE LEITURA.
 *
 * Responde, com números reais da conta Jumbonline:
 *
 * 1. Qual preço a API de Preço devolve para cada código de serviço
 *    (contrato × à vista/balcão), com e sem nuContrato/nuDR — ou seja, se o
 *    checkout está cobrando tabela de contrato ou tabela de balcão.
 * 2. Quanto o checkout cobra hoje (contrato + 46%) para o mesmo envio.
 * 3. A margem real dos últimos N dias: frete cobrado do cliente (orders)
 *    × custo oficial dos Correios (shipments.cost = precoPrePostagem).
 *
 * Não grava nada, não cria pré-postagem e não altera o token em cache.
 */
class AnalisarPrecoFrete extends Command
{
    protected $signature = 'frete:analise-comercial
        {--prison= : Slug da unidade prisional de destino}
        {--cep= : CEP de destino (alternativa ao --prison)}
        {--peso=1000 : Peso do envio simulado, em gramas}
        {--dias=60 : Janela da análise de margem real}';

    protected $description = 'Compara tabela de contrato × balcão na API de Preço e mede a margem real do frete';

    /** Mesmo CEP de origem do checkout (Purchase) e do PDV. */
    private const CEP_ORIGEM = '02737050';

    /** Mesma margem aplicada hoje em Purchase::updateShippingPrice(). */
    private const MARGEM_ATUAL = 46;

    private const SERVICOS = [
        '03220' => ['SEDEX', 'Contrato agência (usado no checkout)'],
        '03298' => ['PAC', 'Contrato agência'],
        '03050' => ['SEDEX', 'À vista / balcão'],
        '03085' => ['PAC', 'À vista / balcão'],
        '04014' => ['SEDEX', 'Varejo (código antigo)'],
        '04510' => ['PAC', 'Varejo (código antigo)'],
    ];

    public function handle(): int
    {
        // Destino
        if ($this->option('prison')) {
            $unidade = PrisonUnit::where('slug', $this->option('prison'))->first();

            if (! $unidade) {
                $this->error("Unidade '{$this->option('prison')}' não encontrada.");

                return self::FAILURE;
            }

            $cepDestino = preg_replace('/\D/', '', (string) $unidade->cep);
        } else {
            $cepDestino = preg_replace('/\D/', '', (string) $this->option('cep'));
        }

        if (strlen($cepDestino) !== 8) {
            $this->error('Informe --prison=SLUG ou --cep=CEP válido.');

            return self::FAILURE;
        }

        $peso = max(1, (int) $this->option('peso'));
        $pacote = app(PackageEstimator::class)->estimarPorPeso($peso);

        $this->info("Simulação: {$peso} g · {$pacote->descricao()} · {$this->origemDestino($cepDestino)}");

        // Token novo, só em memória (não mexe no cache usado pelo checkout)
        $metodo = ShippingMethod::where('identifier', 'correios')->firstOrFail();
        $host = rtrim(config('correios.host'), '/');

        $auth = json_decode((string) (new Client([
            'auth' => [$metodo->credentials['user_key'], $metodo->credentials['access_key']],
        ]))->post($host . '/token/v1/autentica/cartaopostagem', [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body' => json_encode(['numero' => $metodo->credentials['cartaopostagem']]),
        ])->getBody(), true);

        $token = $auth['token'] ?? null;
        $contrato = $auth['cartaoPostagem']['contrato'] ?? ($metodo->credentials['contrato'] ?? null);
        $dr = $auth['cartaoPostagem']['dr'] ?? null;

        if (! $token) {
            $this->error('Não foi possível obter o token dos Correios.');

            return self::FAILURE;
        }

        $this->line("Contrato: " . ($contrato ?: '—') . " · DR: " . ($dr ?: '— (não veio no token; consulta "com contrato" será pulada)'));
        $this->newLine();

        // 1. Preço por código de serviço
        $linhas = [];
        $precoContratoSedex = null;

        foreach (self::SERVICOS as $codigo => [$servico, $modalidade]) {
            $semContrato = $this->consultar($host, $token, $codigo, $cepDestino, $pacote->toPrecoQuery());
            $comContrato = ($contrato && $dr)
                ? $this->consultar($host, $token, $codigo, $cepDestino, $pacote->toPrecoQuery() + ['nuContrato' => $contrato, 'nuDR' => $dr])
                : 'pulado';

            if ($codigo === '03220' && is_float($semContrato)) {
                $precoContratoSedex = $semContrato;
            }

            $linhas[] = [$codigo, $servico, $modalidade, $this->formatar($semContrato), $this->formatar($comContrato)];
        }

        $this->table(['Código', 'Serviço', 'Modalidade', 'Sem nuContrato', 'Com nuContrato/nuDR'], $linhas);

        if ($precoContratoSedex !== null) {
            $checkout = $precoContratoSedex + round(($precoContratoSedex * self::MARGEM_ATUAL) / 100);
            $this->line('Checkout cobra hoje para este envio: <comment>R$ ' . number_format($checkout, 2, ',', '.') . '</comment> (03220 sem contrato + ' . self::MARGEM_ATUAL . '%, arredondado em reais)');
        }

        // 2. Margem real
        $this->newLine();
        $this->info('Margem real — últimos ' . (int) $this->option('dias') . ' dias (frete cobrado × precoPrePostagem)');

        $envios = Shipment::query()
            ->where('shipping_carrier', ShippingCarrier::CORREIOS->value)
            ->whereNotNull('cost')
            ->where('created_at', '>=', now()->subDays((int) $this->option('dias')))
            ->with('order:id,shipping_price')
            ->get()
            ->filter(fn (Shipment $s) => $s->order !== null);

        $pagos = $envios->filter(fn ($s) => (float) $s->order->shipping_price > 0);
        $gratis = $envios->count() - $pagos->count();

        if ($pagos->isEmpty()) {
            $this->line('   Sem envios com custo registrado e frete cobrado no período.');

            return self::SUCCESS;
        }

        $cobrado = $pagos->sum(fn ($s) => (float) $s->order->shipping_price);
        $custo = $pagos->sum(fn ($s) => (float) $s->cost);
        $margens = $pagos->map(fn ($s) => (float) $s->order->shipping_price - (float) $s->cost)->sort()->values();
        $negativos = $margens->filter(fn ($m) => $m < 0)->count();

        $this->table(['Indicador', 'Valor'], [
            ['Envios com frete cobrado', $pagos->count()],
            ['Envios com frete grátis (custo absorvido)', $gratis . ' · R$ ' . number_format($envios->diff($pagos)->sum(fn ($s) => (float) $s->cost), 2, ',', '.')],
            ['Frete cobrado (total)', 'R$ ' . number_format($cobrado, 2, ',', '.')],
            ['Custo Correios (total)', 'R$ ' . number_format($custo, 2, ',', '.')],
            ['Margem média por envio', 'R$ ' . number_format(($cobrado - $custo) / $pagos->count(), 2, ',', '.')],
            ['Menor margem / mediana', 'R$ ' . number_format($margens->first(), 2, ',', '.') . ' / R$ ' . number_format($margens->median(), 2, ',', '.')],
            ['Envios com margem negativa', $negativos],
            ['Cobrado ÷ custo', number_format($cobrado / max($custo, 0.01), 2, ',', '.') . '×'],
        ]);

        return self::SUCCESS;
    }

    private function consultar(string $host, string $token, string $codigo, string $cepDestino, array $query): float|string
    {
        $url = $host . '/preco/v1/nacional/' . $codigo . '?' . http_build_query(['cepOrigem' => self::CEP_ORIGEM, 'cepDestino' => $cepDestino] + $query);

        try {
            $response = (new Client())->get($url, [
                'http_errors' => false,
                'headers' => ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token],
            ]);
        } catch (\Throwable $e) {
            return 'erro: ' . mb_substr($e->getMessage(), 0, 60);
        }

        $data = json_decode((string) $response->getBody(), true);

        if (! empty($data['pcFinal'])) {
            return (float) str_replace(',', '.', str_replace('.', '', $data['pcFinal']));
        }

        $mensagem = $data['msgs'][0] ?? $data['txErro'] ?? ('HTTP ' . $response->getStatusCode());

        return 'recusado: ' . mb_substr((string) $mensagem, 0, 60);
    }

    private function formatar(float|string $valor): string
    {
        return is_float($valor) ? 'R$ ' . number_format($valor, 2, ',', '.') : $valor;
    }

    private function origemDestino(string $cepDestino): string
    {
        return self::CEP_ORIGEM . ' → ' . $cepDestino;
    }
}