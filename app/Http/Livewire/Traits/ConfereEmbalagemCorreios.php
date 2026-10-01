<?php

namespace App\Http\Livewire\Traits;

use App\Enums\ShippingServices;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShippingBox;
use App\Services\CorreiosFreightService;
use App\Services\CorreiosPostagemOrchestrator;
use App\Services\CorreiosPrepostagemService;
use App\Services\Shipping\PackageDimensions;
use App\Services\Shipping\PackageEstimator;
use Illuminate\Support\Facades\Log;

/**
 * Conferência de embalagem no balcão, ANTES de criar a pré-postagem oficial.
 *
 * Compartilhado por /admin/correios (CorreiosPostagem) e pela ação embutida
 * no detalhe do pedido (OrderCorreiosAction), para que os dois pontos de
 * criação passem obrigatoriamente pelo mesmo fluxo:
 *
 * 1. abrirConferencia(): pré-preenche a caixa e o peso estimados pelo sistema.
 * 2. O atendente mede a caixa física, troca de caixa com um clique ou digita
 *    comprimento/largura/altura/peso reais.
 * 3. cotarFrete(): mostra o custo Correios da estimativa × das medidas reais.
 * 4. confirmarPostagem(): cria a pré-postagem com as medidas conferidas; o
 *    frete final oficial (precoPrePostagem) fica gravado em shipments.cost.
 *
 * O modal de conferência substitui o wire:confirm, que não existe no
 * Livewire 2 e era ignorado em silêncio (um clique criava o envio real).
 *
 * O componente que usa este trait precisa ter $remetenteManual,
 * $destinatarioManual e rulesManual() (já existentes nos dois componentes)
 * e implementar aposConfirmarPostagem().
 */
trait ConfereEmbalagemCorreios
{
    public bool $mostrarConferencia = false;

    public ?int $conferenciaOrderId = null;

    public bool $conferenciaManual = false;

    public array $conferencia = [
        'caixa_id' => null,
        'comprimento' => '',
        'largura' => '',
        'altura' => '',
        'peso_kg' => '',
    ];

    public array $conferenciaEstimativa = [];

    public array $conferenciaPedido = [];

    public ?float $custoEstimado = null;

    public ?float $custoConferido = null;

    public bool $cotacaoDesatualizada = true;

    public ?string $erroCotacao = null;

    /**
     * Chamado depois que a pré-postagem foi criada com sucesso.
     */
    abstract protected function aposConfirmarPostagem(Shipment $shipment): void;

    protected function rulesConferencia(): array
    {
        return [
            'conferencia.comprimento' => ['required', 'integer', 'min:' . PackageEstimator::MIN_COMPRIMENTO, 'max:' . PackageEstimator::MAX_LADO],
            'conferencia.largura' => ['required', 'integer', 'min:' . PackageEstimator::MIN_LARGURA, 'max:' . PackageEstimator::MAX_LADO],
            'conferencia.altura' => ['required', 'integer', 'min:' . PackageEstimator::MIN_ALTURA, 'max:' . PackageEstimator::MAX_LADO],
            'conferencia.peso_kg' => ['required', 'numeric', 'min:0.001', 'max:30'],
        ];
    }

    protected function mensagensConferencia(): array
    {
        return [
            'conferencia.comprimento.required' => 'Informe o comprimento.',
            'conferencia.comprimento.min' => 'Mínimo dos Correios: ' . PackageEstimator::MIN_COMPRIMENTO . ' cm.',
            'conferencia.comprimento.max' => 'Máximo: ' . PackageEstimator::MAX_LADO . ' cm.',
            'conferencia.largura.required' => 'Informe a largura.',
            'conferencia.largura.min' => 'Mínimo dos Correios: ' . PackageEstimator::MIN_LARGURA . ' cm.',
            'conferencia.largura.max' => 'Máximo: ' . PackageEstimator::MAX_LADO . ' cm.',
            'conferencia.altura.required' => 'Informe a altura.',
            'conferencia.altura.min' => 'Mínimo dos Correios: ' . PackageEstimator::MIN_ALTURA . ' cm.',
            'conferencia.altura.max' => 'Máximo: ' . PackageEstimator::MAX_LADO . ' cm.',
            'conferencia.peso_kg.required' => 'Informe o peso da balança.',
            'conferencia.peso_kg.min' => 'Peso inválido.',
            'conferencia.peso_kg.max' => 'Máximo dos Correios: 30 kg.',
            'conferencia.*.integer' => 'Use centímetros inteiros.',
            'conferencia.*.numeric' => 'Valor numérico inválido.',
        ];
    }

    public function abrirConferencia(int $orderId, bool $manual = false): void
    {
        $this->resetErrorBag();

        if (Shipment::where('order_id', $orderId)->exists()) {
            $this->addError('postagem', "O pedido #{$orderId} já tem uma remessa registrada — nada foi criado de novo.");

            return;
        }

        $order = Order::with(['orderItems.variant', 'prison_unit', 'customer:id,name', 'detento'])->findOrFail($orderId);

        $estimativa = app(CorreiosPrepostagemService::class)->estimarPacote($order);

        $this->conferenciaOrderId = $order->id;
        $this->conferenciaManual = $manual;
        $this->conferenciaEstimativa = $estimativa->toArray();

        $this->conferencia = [
            'caixa_id' => $estimativa->caixaId,
            'comprimento' => $estimativa->comprimento,
            'largura' => $estimativa->largura,
            'altura' => $estimativa->altura,
            'peso_kg' => round($estimativa->pesoGramas / 1000, 3),
        ];

        $this->conferenciaPedido = [
            'id' => $order->id,
            'cliente' => $order->customer?->name,
            'destino' => $manual
                ? trim(($this->destinatarioManual['nome'] ?? '') . ' — ' . ($this->destinatarioManual['cidade'] ?? '') . '/' . ($this->destinatarioManual['uf'] ?? ''))
                : trim(($order->detento?->name ?? '') . ' — ' . ($order->prison_unit?->name ?? '')),
            'frete_cobrado' => (float) $order->shipping_price,
            'itens' => (int) $order->orderItems->sum('quantity'),
        ];

        $this->custoEstimado = null;
        $this->custoConferido = null;
        $this->cotacaoDesatualizada = true;
        $this->erroCotacao = null;
        $this->mostrarConferencia = true;

        // A cotação (2 chamadas à API de preço) roda logo após o modal abrir,
        // para o atendente não esperar o Correios para ver o formulário.
        $this->dispatchBrowserEvent('correios-conferencia-aberta', ['id' => $this->id]);
    }

    public function selecionarCaixa(int $boxId): void
    {
        $box = ShippingBox::find($boxId);

        if (! $box) {
            return;
        }

        $this->conferencia['caixa_id'] = $box->id;
        $this->conferencia['comprimento'] = $box->length_cm;
        $this->conferencia['largura'] = $box->width_cm;
        $this->conferencia['altura'] = $box->height_cm;

        $this->resetErrorBag(['conferencia.comprimento', 'conferencia.largura', 'conferencia.altura']);
        $this->marcarCotacaoDesatualizada();
    }

    public function restaurarEstimativa(): void
    {
        if (empty($this->conferenciaEstimativa)) {
            return;
        }

        $estimativa = PackageDimensions::fromArray($this->conferenciaEstimativa);

        $this->conferencia = [
            'caixa_id' => $estimativa->caixaId,
            'comprimento' => $estimativa->comprimento,
            'largura' => $estimativa->largura,
            'altura' => $estimativa->altura,
            'peso_kg' => round($estimativa->pesoGramas / 1000, 3),
        ];

        $this->resetErrorBag();
        $this->marcarCotacaoDesatualizada();
    }

    /**
     * Medida digitada que não bate com a caixa selecionada vira "medida
     * personalizada" (caixa_id = null), para o histórico não atribuir a uma
     * caixa do cadastro uma embalagem que não é ela.
     */
    public function updatedConferencia($value, $key): void
    {
        if (in_array($key, ['comprimento', 'largura', 'altura'], true) && $this->conferencia['caixa_id']) {
            $box = ShippingBox::find($this->conferencia['caixa_id']);

            if (! $box
                || (int) $this->conferencia['comprimento'] !== $box->length_cm
                || (int) $this->conferencia['largura'] !== $box->width_cm
                || (int) $this->conferencia['altura'] !== $box->height_cm) {
                $this->conferencia['caixa_id'] = null;
            }
        }

        $this->marcarCotacaoDesatualizada();
    }

    /**
     * Mesmo CEP de origem fixo da cotação do site (Purchase) e do PDV
     * (ShippingController), para a prévia ser comparável ao frete cobrado.
     */
    protected function cepOrigemCorreios(): string
    {
        return '02737050';
    }

    protected function marcarCotacaoDesatualizada(): void
    {
        $this->custoConferido = null;
        $this->cotacaoDesatualizada = true;
        $this->erroCotacao = null;
    }

    protected function validarConferencia(): bool
    {
        $this->validate($this->rulesConferencia(), $this->mensagensConferencia());

        $soma = (int) $this->conferencia['comprimento'] + (int) $this->conferencia['largura'] + (int) $this->conferencia['altura'];

        if ($soma > PackageEstimator::MAX_SOMA_LADOS) {
            $this->addError('conferencia.comprimento', "A soma das medidas ({$soma} cm) passa do limite de " . PackageEstimator::MAX_SOMA_LADOS . ' cm dos Correios.');

            return false;
        }

        return true;
    }

    public function cotarFrete(): void
    {
        if (! $this->conferenciaOrderId || ! $this->validarConferencia()) {
            return;
        }

        $order = Order::with('prison_unit')->find($this->conferenciaOrderId);

        if (! $order) {
            return;
        }

        $cepDestino = preg_replace('/\D/', '', (string) ($this->conferenciaManual
            ? ($this->destinatarioManual['cep'] ?? '')
            : $order->prison_unit?->cep));

        if (strlen($cepDestino) !== 8) {
            $this->erroCotacao = 'CEP de destino inválido — não é possível cotar. A pré-postagem ainda pode ser criada.';

            return;
        }

        $service = ShippingServices::tryFrom((string) ($order->shipping_method_code ?? '')) ?? ShippingServices::SEDEX_CONTRATO_AG;
        $freight = app(CorreiosFreightService::class);
        $conferido = $this->pacoteConferido();

        try {
            if ($this->custoEstimado === null && ! empty($this->conferenciaEstimativa)) {
                $estimativa = PackageDimensions::fromArray($this->conferenciaEstimativa);
                $this->custoEstimado = $freight->consultarPrecoCorreios($this->cepOrigemCorreios(), $cepDestino, $estimativa->pesoGramas, $service, $estimativa);
            }

            $this->custoConferido = $freight->consultarPrecoCorreios($this->cepOrigemCorreios(), $cepDestino, $conferido->pesoGramas, $service, $conferido);
        } catch (\Throwable $e) {
            Log::warning('[ConferenciaEmbalagem] Falha ao cotar frete', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            $this->custoConferido = null;
        }

        $this->cotacaoDesatualizada = false;
        $this->erroCotacao = $this->custoConferido === null
            ? 'Os Correios não retornaram preço agora. Você pode tentar de novo ou criar a pré-postagem mesmo assim — o valor oficial vem na resposta da pré-postagem.'
            : null;
    }

    public function confirmarPostagem(): void
    {
        if (! $this->conferenciaOrderId || ! $this->validarConferencia()) {
            return;
        }

        // Revalida remetente/destinatário digitados: a conferência manual só
        // deve chegar aqui depois do formulário de postagem manual.
        if ($this->conferenciaManual) {
            $this->validate($this->rulesManual());
        }

        $orderId = $this->conferenciaOrderId;
        $manual = $this->conferenciaManual;
        $pacote = $this->pacoteParaEnvio();

        try {
            $shipment = app(CorreiosPostagemOrchestrator::class)->criar(
                $orderId,
                $manual ? $this->remetenteManual : null,
                $manual ? $this->destinatarioManual : null,
                $pacote
            );
        } catch (\Throwable $e) {
            $this->mostrarConferencia = false;
            $this->addError('postagem', $e->getMessage());

            return;
        }

        $this->mostrarConferencia = false;
        $this->conferenciaOrderId = null;
        $this->conferenciaManual = false;

        $custo = $shipment->cost !== null ? ' Frete Correios: R$ ' . number_format((float) $shipment->cost, 2, ',', '.') . '.' : '';

        $this->notify(trans('Pré-postagem criada com a embalagem conferida.') . $custo);

        $this->aposConfirmarPostagem($shipment);
    }

    protected function pacoteConferido(): PackageDimensions
    {
        $caixaId = $this->conferencia['caixa_id'] ? (int) $this->conferencia['caixa_id'] : null;

        return PackageDimensions::manual(
            (int) $this->conferencia['comprimento'],
            (int) $this->conferencia['largura'],
            (int) $this->conferencia['altura'],
            (int) round(((float) $this->conferencia['peso_kg']) * 1000),
            $caixaId,
            $caixaId ? ShippingBox::whereKey($caixaId)->value('name') : null,
        );
    }

    /**
     * Sem nenhuma alteração do atendente, envia a própria estimativa (mantém
     * a origem "caixa"/"estimativa" no histórico); com qualquer alteração,
     * envia as medidas conferidas como "manual".
     */
    protected function pacoteParaEnvio(): PackageDimensions
    {
        $conferido = $this->pacoteConferido();

        if (empty($this->conferenciaEstimativa)) {
            return $conferido;
        }

        $estimativa = PackageDimensions::fromArray($this->conferenciaEstimativa);

        $inalterado = $conferido->comprimento === $estimativa->comprimento
            && $conferido->largura === $estimativa->largura
            && $conferido->altura === $estimativa->altura
            && $conferido->pesoGramas === $estimativa->pesoGramas
            && $conferido->caixaId === $estimativa->caixaId;

        return $inalterado ? $estimativa : $conferido;
    }

    public function getCaixasConferenciaProperty()
    {
        try {
            return ShippingBox::active()->get()->sortBy(fn (ShippingBox $box) => $box->volume_cm3)->values();
        } catch (\Throwable $e) {
            return collect();
        }
    }
}