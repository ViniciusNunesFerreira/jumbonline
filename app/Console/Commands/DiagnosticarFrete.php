<?php


namespace App\Console\Commands;

use App\Concerns\AuthenticatesWithCorreios;
use App\Enums\ShippingServices;
use App\Models\Cart;
use App\Models\PrisonUnit;
use App\Models\Promotion;
use App\Models\ShippingBox;
use App\Services\Shipping\PackageDimensions;
use App\Services\Shipping\PackageEstimator;
use App\Settings\ShippingSetting;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Reproduz, SOMENTE LEITURA, exatamente a cotação que o checkout faz em
 * Purchase::updateShippingPrice() → Traits\Correios::calcPrecoFrete(), e
 * mostra cada etapa que pode zerar o frete:
 *
 * 1. Promoção de frete grátis (zera o frete de propósito).
 * 2. CEP de destino da unidade prisional.
 * 3. Parâmetros e caixas do PackageEstimator.
 * 4. Token dos Correios.
 * 5. Resposta CRUA da API de Preço — com a embalagem estimada e, para
 *    comparação, com a caixa antiga 54×36×27 (teste A/B).
 *
 * Não grava nada no banco e não cria pré-postagem: só faz GET na API de
 * preço. Seguro para rodar em staging e produção.
 */
class DiagnosticarFrete extends Command
{
    use AuthenticatesWithCorreios;

    protected $signature = 'frete:diagnosticar
        {--cart= : ID do carrinho (usa o peso e o subtotal reais, como o checkout)}
        {--peso= : Peso total em gramas (alternativa ao --cart)}
        {--prison= : Slug da unidade prisional (de onde vem o CEP de destino)}
        {--cep= : CEP de destino direto (alternativa ao --prison)}';

    protected $description = 'Diagnostica a cotação de frete do checkout passo a passo, sem gravar nada';

    /** Mesmo CEP de origem fixo usado em Purchase::updateShippingPrice(). */
    private const CEP_ORIGEM = '02737050';

    public function handle(): int
    {
        // 1. Peso e subtotal
        $subtotal = null;

        if ($this->option('cart')) {
            $cart = Cart::with('items.variant')->find($this->option('cart'));

            if (! $cart) {
                $this->error("Carrinho #{$this->option('cart')} não encontrado.");

                return self::FAILURE;
            }

            // Exatamente a mesma expressão do Purchase (sem arredondar).
            $peso = $cart->weight * 1000;
            $subtotal = (float) $cart->subtotal;

            $this->info("Carrinho #{$cart->id}: {$cart->items->count()} item(ns), subtotal R$ " . number_format($subtotal, 2, ',', '.'));

            foreach ($cart->items as $item) {
                $variant = $item->variant;
                $this->line(sprintf(
                    '   • variant #%s × %d — peso cadastrado: %s %s',
                    $variant?->id ?? '?',
                    $item->quantity,
                    $variant?->weight_value ?? 'NULL',
                    $variant?->weight_unit ?? ''
                ));
            }
        } elseif ($this->option('peso') !== null) {
            $peso = (float) $this->option('peso');
        } else {
            $this->error('Informe --cart=ID ou --peso=GRAMAS.');

            return self::FAILURE;
        }

        $this->line('psObjeto que o checkout envia: <comment>' . $peso . '</comment>');

        if ($peso <= 0) {
            $this->warn('⚠ Peso zero: os itens do carrinho não têm peso cadastrado. Os Correios rejeitam psObjeto=0 e o checkout deixa o frete em 0.');
        }

        // 2. CEP de destino
        if ($this->option('prison')) {
            $unidade = PrisonUnit::where('slug', $this->option('prison'))->first();

            if (! $unidade) {
                $this->error("Unidade com slug '{$this->option('prison')}' não encontrada.");

                return self::FAILURE;
            }

            $cepDestino = preg_replace('/\D/', '', (string) $unidade->cep);
            $this->info("Unidade: {$unidade->name} — CEP cadastrado: '{$unidade->cep}'");
        } elseif ($this->option('cep')) {
            $cepDestino = preg_replace('/\D/', '', (string) $this->option('cep'));
        } else {
            $this->error('Informe --prison=SLUG ou --cep=CEP.');

            return self::FAILURE;
        }

        if (strlen($cepDestino) !== 8) {
            $this->warn("⚠ CEP de destino inválido ('{$cepDestino}'): os Correios vão rejeitar e o frete fica em 0.");
        }

        // 3. Promoção de frete grátis
        $this->newLine();
        $this->info('— Promoção de frete grátis');

        $promocao = Promotion::where('is_enabled', 1)->first();

        if (! $promocao) {
            $this->line('   Nenhuma promoção ativa.');
        } else {
            $this->line("   Ativa: '{$promocao->name}' — valor mínimo: R$ " . number_format((float) $promocao->os_value, 2, ',', '.') . " (bruto no banco: {$promocao->getRawOriginal('os_value')})");

            if ($subtotal !== null && $subtotal >= $promocao->os_value) {
                $this->warn('   ⚠ Este carrinho ATINGE o mínimo: o checkout zera o frete DE PROPÓSITO, sem consultar os Correios.');
            } elseif ($subtotal === null) {
                $this->line('   (use --cart para saber se o carrinho atinge o mínimo)');
            }

            if ((float) $promocao->os_value <= 0) {
                $this->warn('   ⚠ Valor mínimo é zero: TODO pedido sai com frete grátis.');
            }
        }

        // 4. Estimador
        $this->newLine();
        $this->info('— Estimativa de embalagem');

        try {
            $settings = app(ShippingSetting::class);
            $this->line("   Settings: densidade {$settings->estimated_density} g/cm³, margem {$settings->packaging_margin_percent}%");
        } catch (\Throwable $e) {
            $this->warn('   ⚠ ShippingSetting indisponível (settings migration não rodou?) — estimador usa os defaults. ' . $e->getMessage());
        }

        try {
            $caixas = ShippingBox::active()->get();
            $this->line('   Caixas ativas: ' . ($caixas->isEmpty() ? 'nenhuma' : $caixas->map(fn ($c) => "{$c->code} {$c->dimensions_label}")->implode(' | ')));
        } catch (\Throwable $e) {
            $this->warn('   ⚠ Tabela shipping_boxes indisponível (migration não rodou?) — estimativa proporcional. ' . $e->getMessage());
        }

        $pacote = app(PackageEstimator::class)->estimarPorPeso($peso);
        $this->line('   Escolhida: <comment>' . $pacote->descricao() . '</comment> — peso cúbico ' . $pacote->pesoCubicoKg() . ' kg');

        // 5. Token
        $this->newLine();
        $this->info('— Token dos Correios');

        try {
            $token = $this->correiosToken();
            $this->line('   OK (' . mb_substr($token, 0, 12) . '…)');
        } catch (\Throwable $e) {
            $this->error('   ✘ Falha ao obter token: ' . $e->getMessage());

            return self::FAILURE;
        }

        // 6. Teste A/B na API de preço
        $this->newLine();
        $this->info('— API de Preço (SEDEX contrato, mesmo serviço do checkout)');

        $antiga = new PackageDimensions(54, 36, 27, max(1, (int) ceil($peso)), PackageDimensions::ORIGEM_CAIXA, 0, null, 'Caixa antiga fixa');

        $resultados = [];

        foreach (['Embalagem estimada' => $pacote, 'Caixa antiga 54×36×27' => $antiga] as $rotulo => $p) {
            $resultados[$rotulo] = $this->consultar($token, $cepDestino, $peso, $p, $rotulo);
        }

        // 7. Conclusão
        $this->newLine();
        $ok = array_filter($resultados);

        if (count($ok) === 2) {
            $this->info('✔ Os Correios cotam normalmente com as duas embalagens. Se o checkout mostra R$ 0,00, a causa está antes da API: promoção de frete grátis, CEP da unidade escolhida no checkout ou peso do carrinho — veja os avisos acima.');
        } elseif (empty($resultados['Embalagem estimada']) && ! empty($resultados['Caixa antiga 54×36×27'])) {
            $this->error('✘ Só a embalagem estimada é rejeitada: problema nas dimensões. Envie esta saída para correção.');
        } elseif (empty($ok)) {
            $this->error('✘ Os Correios rejeitam as duas: a causa não é a embalagem (credencial, contrato, serviço, CEP ou peso). Veja a resposta crua acima.');
        }

        return self::SUCCESS;
    }

    private function consultar(string $token, string $cepDestino, float $peso, PackageDimensions $p, string $rotulo): ?string
    {
        // URL montada exatamente como em Traits\Correios::calcPrecoFrete().
        $url = rtrim(config('correios.host'), '/') . '/preco/v1/nacional/' . ShippingServices::SEDEX_CONTRATO_AG->value
            . '?cepDestino=' . $cepDestino
            . '&cepOrigem=' . self::CEP_ORIGEM
            . '&psObjeto=' . $peso
            . '&tpObjeto=2&comprimento=' . $p->comprimento . '&largura=' . $p->largura . '&altura=' . $p->altura;

        $response = (new Client())->get($url, [
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $body = (string) $response->getBody();
        $data = json_decode($body);

        $this->line("   [{$rotulo}] {$p->comprimento}×{$p->largura}×{$p->altura} → HTTP {$response->getStatusCode()}");
        $this->line('      ' . $url);

        if (! empty($data->pcFinal)) {
            $this->line("      <info>pcFinal: R$ {$data->pcFinal}</info>");

            return (string) $data->pcFinal;
        }

        $this->line('      <error>Sem pcFinal.</error> Resposta: ' . mb_substr($body, 0, 600));

        return null;
    }
}