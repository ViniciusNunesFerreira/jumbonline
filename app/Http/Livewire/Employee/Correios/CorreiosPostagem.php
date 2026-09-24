<?php

namespace App\Http\Livewire\Employee\Correios;

use App\Enums\CorreiosPrepostagemStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingCarrier;
use App\Jobs\AguardarStatusPrepostagemJob;
use App\Jobs\SolicitarRotuloCorreiosJob;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Visitante;
use App\Services\CorreiosPrepostagemService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CorreiosPostagemOrchestrator;

class CorreiosPostagem extends Component
{
    use WithPagination;

    public string $tab = 'pendentes';

    public string $search = '';

    public ?int $visitanteEditandoId = null;

    public string $cpfEditando = '';

    public ?int $pedidoManualId = null;

    public array $remetenteManual = ['nome' => '', 'cpf' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];

    public array $destinatarioManual = ['nome' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];

    protected $queryString = ['tab'];

    protected function rulesCpf()
    {
        return ['cpfEditando' => 'required|digits:11'];
    }

    protected function rulesManual()
    {
        return [
            'remetenteManual.nome' => 'required|string|max:50',
            'remetenteManual.cpf' => 'required|digits:11',
            'remetenteManual.cep' => 'required|digits:8',
            'remetenteManual.logradouro' => 'required|string|max:50',
            'remetenteManual.numero' => 'required|string|max:6',
            'remetenteManual.bairro' => 'required|string|max:30',
            'remetenteManual.cidade' => 'required|string|max:30',
            'remetenteManual.uf' => 'required|string|size:2',
            'destinatarioManual.nome' => 'required|string|max:50',
            'destinatarioManual.cep' => 'required|digits:8',
            'destinatarioManual.logradouro' => 'required|string|max:50',
            'destinatarioManual.numero' => 'required|string|max:6',
            'destinatarioManual.bairro' => 'required|string|max:30',
            'destinatarioManual.cidade' => 'required|string|max:30',
            'destinatarioManual.uf' => 'required|string|size:2',
        ];
    }

    protected $messages = [
        'cpfEditando.required' => 'Informe o CPF.',
        'cpfEditando.digits' => 'CPF precisa ter 11 dígitos numéricos.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setTab(string $tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function getPedidosPendentesProperty()
    {
        return Order::query()
            ->whereHas('payments', fn($q) => $q->where('status', PaymentStatus::PAID->name))
            ->whereDoesntHave('shipments')
            ->with(['customer:id,name', 'detento', 'visitante', 'prison_unit'])
            ->when($this->search, fn($q) => $q->whereHas('customer', fn($c) => $c->where('name', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate(15);
    }

    public function getEmProcessamentoProperty()
    {
        return Shipment::query()
            ->where('shipping_carrier', ShippingCarrier::CORREIOS->value)
            ->whereNotNull('correios_status')
            ->where('correios_status', '!=', CorreiosPrepostagemStatus::POSTADO->value)
            ->whereNull('correios_label_recibo') // Aplica AND no filtro
            ->with('order.customer:id,name')
            ->latest()
            ->paginate(15);
    }

    public function getConcluidosProperty()
    {
        return Shipment::query()
            ->where('shipping_carrier', ShippingCarrier::CORREIOS->value)
            ->whereNotNull('correios_label_recibo')
            ->with('order.customer:id,name')
            ->latest()
            ->paginate(15);
    }

    public function abrirEdicaoCpf($visitanteId)
    {
        $this->visitanteEditandoId = $visitanteId;
        $this->cpfEditando = '';
        $this->resetErrorBag();
    }

    public function salvarCpf()
    {
        $this->validate($this->rulesCpf());

        Visitante::whereKey($this->visitanteEditandoId)->update(['cpf' => $this->cpfEditando]);

        $this->visitanteEditandoId = null;

        $this->notify(trans('CPF cadastrado. Já pode criar a pré-postagem.'));
    }

    public function abrirPostagemManual($orderId)
    {
        $this->pedidoManualId = $orderId;
        $this->remetenteManual = ['nome' => '', 'cpf' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];
        $this->destinatarioManual = ['nome' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];
        $this->resetErrorBag();
    }

    public function criarPostagemManual(CorreiosPrepostagemService $service)
    {
        $this->validate($this->rulesManual());

        $orderId = $this->pedidoManualId;
        $this->pedidoManualId = null;

        $this->criarPostagem($orderId, $service, $this->remetenteManual, $this->destinatarioManual);
    }

    public function criarPostagem($orderId, CorreiosPostagemOrchestrator $orchestrator, ?array $remetenteManual = null, ?array $destinatarioManual = null)
    {
        try {
            $orchestrator->criar($orderId, $remetenteManual, $destinatarioManual);
        } catch (\Throwable $e) {
            $this->addError('postagem', $e->getMessage());
            return;
        }

        $this->notify(trans('Pré-postagem criada. Acompanhe em "Em processamento".'));
        $this->setTab('processamento');
    }

    public function baixarRotulo($shipmentId)
    {
        $shipment = Shipment::findOrFail($shipmentId);
        $path = "correios-labels/{$shipment->id}.pdf";

        if (! Storage::disk('local')->exists($path)) {
            $this->notify(trans('Rótulo ainda não está pronto — aguarde alguns instantes.'));
            return;
        }

        return response()->download(Storage::disk('local')->path($path), "etiqueta-pedido-{$shipment->order_id}.pdf");
    }

    public function baixarDeclaracao($shipmentId, CorreiosPrepostagemService $service)
    {
        $shipment = Shipment::findOrFail($shipmentId);

        try {
            $html = $service->declaracaoConteudo($shipment->correios_prepostagem_id);
        } catch (\Throwable $e) {
            $this->notify(trans('Não foi possível gerar a declaração agora — tente novamente em instantes.'));
            return;
        }

        $fileName = "declaracao-conteudo-pedido-{$shipment->order_id}.pdf";

        return response()->streamDownload(function () use ($html) {
            echo Pdf::loadHTML($html)->setPaper('a4')->output();
        }, $fileName);
    }

    public function cancelarPostagem($shipmentId, CorreiosPrepostagemService $service)
    {
        $shipment = Shipment::findOrFail($shipmentId);

        try {
            $service->cancelar($shipment->correios_prepostagem_id);
        } catch (\Throwable $e) {
            $this->notify(trans('Não foi possível cancelar junto aos Correios — verifique manualmente.'));
            return;
        }

        $shipment->delete();

        $this->notify(trans('Pré-postagem cancelada.'));
    }

    public function render()
    {
        return view('livewire.employee.correios.correios-postagem', [
            'pedidosPendentes' => $this->tab === 'pendentes' ? $this->pedidosPendentes : null,
            'emProcessamento' => $this->tab === 'processamento' ? $this->emProcessamento : null,
            'concluidos' => $this->tab === 'concluidos' ? $this->concluidos : null,
        ])->layout('layouts.admin');
    }
}