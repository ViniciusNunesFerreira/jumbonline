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
        if ($shipment->correios_status === CorreiosPrepostagemStatus::PENDENTE->value) {
            $this->notify(trans('A declaração eletrônica (DCe) ainda está sendo gerada pelos Correios. Aguarde alguns segundos e tente novamente.'));
            return;
        }

        try {
            $html = $service->declaracaoConteudo($shipment->correios_prepostagem_id);
        } catch (\Throwable $e) {
            $status = method_exists($e, 'getResponse') && $e->getResponse() ? $e->getResponse()->getStatusCode() : 'sem resposta';
            $body = method_exists($e, 'getResponse') && $e->getResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();

            \Illuminate\Support\Facades\Log::error("Correios: falha ao buscar declaração de conteúdo do shipment #{$shipment->id} (prepostagem {$shipment->correios_prepostagem_id}). HTTP {$status}: {$body}");

            $this->notify(trans('Não foi possível gerar a declaração agora — tente novamente em instantes.'));
            return;
        }

        try {
            $html = trim($html);

            // Bloco de controle para injetar no <head> oficial do HTML
            $metaAndCss = '
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 12mm 10mm 12mm 10mm !important;
                    }
                    body, table, td, th, div, span, p {
                        font-family: "DejaVu Sans", sans-serif !important; /* Resolve a acentuação UTF-8 */
                    }
                    body {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    html, body, table, div {
                        height: auto !important;
                        max-height: 100% !important;
                    }
                    /* Elimina a página em branco no final */
                    * {
                        page-break-after: avoid !important;
                        page-break-before: avoid !important;
                        page-break-inside: avoid !important;
                    }
                </style>
            ';

            // Injeta corretamente dentro do <head> para manter a estrutura HTML válida
            if (stripos($html, '<head>') !== false) {
                $htmlPreparado = str_ireplace('<head>', '<head>' . $metaAndCss, $html);
            } else {
                $htmlPreparado = '<html><head>' . $metaAndCss . '</head><body>' . $html . '</body></html>';
            }

            return response()->streamDownload(function () use ($htmlPreparado) {
                echo Pdf::loadHTML($htmlPreparado)
                    ->setPaper('a4', 'portrait')
                    ->setOptions([
                        'defaultMediaType' => 'screen',
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'DejaVu Sans', // Garante o fallback de acentos no Dompdf
                    ])
                    ->output();
            }, "declaracao-conteudo-pedido-{$shipment->order_id}.pdf");

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Correios: HTML da declaração veio, mas o dompdf falhou pro shipment #{$shipment->id}: " . $e->getMessage());
            $this->notify(trans('A Correios retornou a declaração, mas houve um erro ao gerar o PDF. Aviso técnico já registrado.'));
        }
    }

    public function render()
    {
        return view('livewire.employee.order.components.order-correios-action');
    }
}