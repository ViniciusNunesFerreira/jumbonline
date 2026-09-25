<?php

namespace App\Services;

use App\Concerns\AuthenticatesWithCorreios;
use App\Models\Order;
use GuzzleHttp\Client;
use RuntimeException;

class CorreiosPrepostagemService
{
    use AuthenticatesWithCorreios;

    protected function baseUrl(): string
    {
        return rtrim(config('correios.host'), '/') . '/prepostagem';
    }

    protected function client(): Client
    {
        return new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->correiosToken(),
            ],
        ]);
    }

    /**
     * $remetenteManual / $destinatarioManual: quando o pedido não tem
     * visitante vinculado (venda de balcão sem destino a unidade prisional),
     * o atendente preenche remetente e destinatário na hora — nenhum dos
     * dois vem do snapshot/relacionamento automático nesse caso.
     */
    public function criar(Order $order, ?array $remetenteManual = null, ?array $destinatarioManual = null): array
    {
        $payload = $this->montarPayload($order, $remetenteManual, $destinatarioManual);

        $response = $this->client()->post($this->baseUrl() . '/v1/prepostagens', ['json' => $payload]);

        return json_decode($response->getBody(), true);
    }

    public function consultarStatus(string $idPrePostagem): ?array
    {
        $response = $this->client()->get($this->baseUrl() . '/v2/prepostagens', [
            'query' => ['id' => $idPrePostagem],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['itens'][0] ?? null;
    }

    public function solicitarRotulo(string $idPrePostagem): array
    {
        $response = $this->client()->post($this->baseUrl() . '/v1/prepostagens/rotulo/assincrono/pdf', [
            'json' => [
                'idsPrePostagem' => [$idPrePostagem],
                'tipoRotulo' => 'P',
                'formatoRotulo' => 'ET',
                'layoutImpressao' => 'LINEAR_100_150',
                'imprimeRemetente' => 'S',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function consultarRotulo(string $idRecibo): array
    {
        $response = $this->client()->get($this->baseUrl() . "/v1/prepostagens/rotulo/download/assincrono/{$idRecibo}");

        return json_decode($response->getBody(), true);
    }

    

    public function cancelar(string $idPrePostagem): void
    {
        $this->client()->delete($this->baseUrl() . "/v1/prepostagens/{$idPrePostagem}");
    }

    protected function montarPayload(Order $order, ?array $remetenteManual, ?array $destinatarioManual): array
    {
        $remetente = $remetenteManual ?? $this->remetenteDoVisitante($order);
        $destinatario = $destinatarioManual ?? $this->destinatarioDoDetento($order);

        return [
            'remetente' => [
                'nome' => $remetente['nome'],
                'cpfCnpj' => '43221148000169',                   //preg_replace('/\D/', '', $remetente['cpf']),
                'endereco' => [
                    'cep' => preg_replace('/\D/', '', $remetente['cep']),
                    'logradouro' => $remetente['logradouro'],
                    'numero' => $remetente['numero'],
                    'bairro' => $remetente['bairro'],
                    'cidade' => $remetente['cidade'],
                    'uf' => $remetente['uf'],
                ],
            ],
            'destinatario' => [
                'nome' => $destinatario['nome'],
                'obs' => $destinatario['obs'] ?? '',
                'endereco' => [
                    'cep' => preg_replace('/\D/', '', $destinatario['cep']),
                    'logradouro' => $destinatario['logradouro'],
                    'numero' => $destinatario['numero'],
                    'bairro' => $destinatario['bairro'],
                    'cidade' => $destinatario['cidade'],
                    'uf' => $destinatario['uf'],
                    'regiao' => '',
                ],
            ],
            'codigoServico' => $this->codigoServico($order),
            'emiteDCe' => 'S',
            'pesoInformado' => (string) $this->pesoGramas($order),
            'codigoFormatoObjetoInformado' => '2',
            'alturaInformada' => '27',
            'larguraInformada' => '36',
            'comprimentoInformado' => '54',
            'cienteObjetoNaoProibido' => '1',
            'itensDeclaracaoConteudo' => $order->orderItems->map(fn($item) => [
                'conteudo' => \Illuminate\Support\Str::limit($item->name, 60, ''),
                'quantidade' => (string) $item->quantity,
                'valor' => number_format($item->price, 2, '.', ''),
            ])->all(),
        ];
    }

    protected function remetenteDoVisitante(Order $order): array
    {
        $snapshot = $order->visitante_snapshot;

        $remetente = [
            'nome' => $snapshot['nome'] ?? $order->visitante->nome,
            'cpf' => $snapshot['cpf'] ?? $order->visitante->cpf,
            'cep' => $snapshot['cep'] ?? $order->visitante->cep,
            'logradouro' => $snapshot['logradouro'] ?? $order->visitante->logradouro,
            'numero' => $snapshot['numero'] ?? $order->visitante->numero,
            'bairro' => $snapshot['bairro'] ?? $order->visitante->bairro,
            'cidade' => $snapshot['cidade'] ?? $order->visitante->cidade,
            'uf' => $snapshot['uf'] ?? $order->visitante->uf,
        ];

        if (empty($remetente['cpf'])) {
            throw new RuntimeException("O visitante do pedido #{$order->id} não tem CPF cadastrado — é obrigatório para a pré-postagem oficial (ele é o declarante legal). Atualize o cadastro do visitante antes de continuar.");
        }

        return $remetente;
    }

    protected function destinatarioDoDetento(Order $order): array
    {
        $snapshot = $order->detento_snapshot;

        $detento = [
            'name' => $snapshot['name'] ?? $order->detento->name,
            'matricula' => $snapshot['matricula'] ?? $order->detento->matricula,
            'raio' => $snapshot['raio'] ?? $order->detento->raio,
            'cela' => $snapshot['cela'] ?? $order->detento->cela,
        ];

        return [
            'nome' => $detento['name'],
            'obs' => trim("{$detento['matricula']} {$detento['raio']} {$detento['cela']}"),
            'cep' => $order->prison_unit->cep,
            'logradouro' => $order->prison_unit->logradouro,
            'numero' => $order->prison_unit->numero,
            'bairro' => $order->prison_unit->bairro,
            'cidade' => $order->prison_unit->cidade,
            'uf' => $order->prison_unit->uf,
        ];
    }

    public function gerarDeclaracaoPdf(string $idPrePostagem): string
    {
        $dados = $this->consultarStatus($idPrePostagem);

        if (! $dados) {
            throw new RuntimeException("Pré-postagem {$idPrePostagem} não encontrada na Correios.");
        }

        $html = view('employee.correios.declaracao-conteudo', ['d' => $dados])->render();

        return \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOptions(['isHtml5ParserEnabled' => true])
            ->output();
    }

    protected function pesoGramas(Order $order): int
    {
        $kg = $order->orderItems->sum(function ($item) {
            $variant = $item->variant;

            if (! $variant) {
                return 0;
            }

            $peso = \App\Models\Variant::convertWeightToKg($variant->weight_value, $variant->weight_unit);

            return $peso * $item->quantity;
        });

        return max(1, (int) round($kg * 1000));
    }

    protected function codigoServico(Order $order): string
    {
        return $order->shipping_method_code ?? \App\Enums\ShippingServices::SEDEX_CONTRATO_AG->value;
    }
}