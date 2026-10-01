<?php

namespace App\Http\Livewire\Employee\Order\Components;

use App\Http\Livewire\Traits\ConfereEmbalagemCorreios;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\CorreiosPrepostagemService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class OrderCorreiosAction extends Component
{
    use ConfereEmbalagemCorreios;

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
        return $this->order->shipments()->where('shipping_carrier', 'correios')->with('shippingBox:id,code,name')->first();
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

    /**
     * Valida remetente/destinatário e segue para a conferência de embalagem —
     * a pré-postagem só é criada no confirmarPostagem() do trait.
     */
    public function criarManual()
    {
        $this->validate($this->rulesManual());

        $this->showManual = false;

        $this->abrirConferencia($this->order->id, true);
    }

    protected function aposConfirmarPostagem(Shipment $shipment): void
    {
        $this->emit('refresh')->to('employee.order.components.order-timeline');
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
            Log::error("Correios: falha ao gerar declaração de conteúdo do shipment #{$shipment->id}: " . $e->getMessage());
            $this->notify(trans('Não foi possível gerar a declaração agora — tente novamente em instantes.'));
            return;
        }

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, "declaracao-conteudo-pedido-{$shipment->order_id}.pdf");
    }

    public function render()
    {
        return view('livewire.employee.order.components.order-correios-action', [
            'caixasConferencia' => $this->mostrarConferencia ? $this->caixasConferencia : collect(),
        ]);
    }
}