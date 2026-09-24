<?php

namespace App\Http\Livewire\Employee\Order\Components;

use App\Enums\CorreiosPrepostagemStatus;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Visitante;
use App\Services\CorreiosPostagemOrchestrator;
use App\Services\CorreiosPrepostagemService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class OrderCorreiosAction extends Component
{
    public Order $order;

    public bool $showManual = false;

    public array $remetenteManual = ['nome' => '', 'cpf' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];

    public array $destinatarioManual = ['nome' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];

    public bool $showCpf = false;

    public string $cpfEditando = '';

    protected $listeners = ['refresh' => '$refresh'];

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

    public function getShipmentProperty(): ?Shipment
    {
        return $this->order->shipments()->where('shipping_carrier', 'correios')->first();
    }

    public function abrirCpf()
    {
        $this->cpfEditando = '';
        $this->resetErrorBag();
        $this->showCpf = true;
    }

    public function salvarCpf()
    {
        $this->validate(['cpfEditando' => 'required|digits:11']);

        $this->order->visitante->update(['cpf' => $this->cpfEditando]);

        $this->showCpf = false;

        $this->notify(trans('CPF cadastrado. Já pode solicitar a pré-postagem.'));
    }

    public function abrirManual()
    {
        $this->resetErrorBag();
        $this->showManual = true;
    }

    public function criarManual(CorreiosPostagemOrchestrator $orchestrator)
    {
        $this->validate($this->rulesManual());

        $this->criar($orchestrator, $this->remetenteManual, $this->destinatarioManual);

        $this->showManual = false;
    }

    public function criar(CorreiosPostagemOrchestrator $orchestrator, ?array $remetenteManual = null, ?array $destinatarioManual = null)
    {
        try {
            $orchestrator->criar($this->order->id, $remetenteManual, $destinatarioManual);
        } catch (\Throwable $e) {
            $this->addError('postagem', $e->getMessage());
            return;
        }

        $this->notify(trans('Pré-postagem solicitada aos Correios.'));
    }

    public function baixarRotulo()
    {
        $shipment = $this->shipment;
        $path = "correios-labels/{$shipment->id}.pdf";

        if (! Storage::disk('local')->exists($path)) {
            $this->notify(trans('Rótulo ainda não está pronto — aguarde alguns instantes.'));
            return;
        }

        return response()->download(Storage::disk('local')->path($path), "etiqueta-pedido-{$shipment->order_id}.pdf");
    }

    public function baixarDeclaracao(CorreiosPrepostagemService $service)
    {
        $shipment = $this->shipment;

        try {
            $pdf = $service->gerarDeclaracaoPdf($shipment->correios_prepostagem_id);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Correios: falha ao gerar declaração de conteúdo do shipment #{$shipment->id}: " . $e->getMessage());
            $this->notify(trans('Não foi possível gerar a declaração agora — tente novamente em instantes.'));
            return;
        }

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, "declaracao-conteudo-pedido-{$shipment->order_id}.pdf");
    }

    public function render()
    {
        return view('livewire.employee.order.components.order-correios-action');
    }
}