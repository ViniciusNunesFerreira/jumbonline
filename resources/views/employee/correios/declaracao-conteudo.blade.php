<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1.2cm; }
        * { box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 11px; color: #111; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #333; padding: 5px 8px; vertical-align: top; }
        .titulo { text-align: center; font-size: 14px; font-weight: bold; padding: 6px; border: 1px solid #333; border-bottom: none; }
        .cabecalho { background: #e5e5e5; font-weight: bold; text-align: center; }
        .label { font-size: 9px; color: #555; }
        .valor { font-weight: bold; }
        .secao { background: #e5e5e5; font-weight: bold; text-align: center; padding: 6px; border: 1px solid #333; }
        .totais { background: #d0d0d0; font-weight: bold; }
        .declaracao-texto { font-size: 9.5px; line-height: 1.4; padding: 10px; border: 1px solid #333; border-top: none; }
        .assinatura { margin-top: 20px; border-top: 1px solid #333; padding-top: 4px; text-align: center; font-size: 9px; }
        .obs-fiscal { font-size: 8.5px; padding: 6px; border: 1px solid #333; border-top: none; }
    </style>
</head>
<body>
    <div class="titulo">DECLARAÇÃO DE CONTEÚDO</div>

    <table>
        <tr>
            <th class="cabecalho" style="width:50%">REMETENTE</th>
            <th class="cabecalho" style="width:50%">DESTINATÁRIO</th>
        </tr>
        <tr>
            <td>
                <span class="label">NOME:</span> <span class="valor">{{ $d['remetente']['nome'] ?? '' }}</span><br>
                <span class="label">END:</span> {{ $d['remetente']['endereco']['logradouro'] ?? '' }}, {{ $d['remetente']['endereco']['numero'] ?? '' }} - {{ $d['remetente']['endereco']['bairro'] ?? '' }}
            </td>
            <td>
                <span class="label">NOME:</span> <span class="valor">{{ $d['destinatario']['nome'] ?? '' }}</span><br>
                <span class="label">END:</span> {{ $d['destinatario']['endereco']['logradouro'] ?? '' }}, {{ $d['destinatario']['endereco']['numero'] ?? '' }} - {{ $d['destinatario']['endereco']['bairro'] ?? '' }}
                @if(!empty($d['destinatario']['obs']))
                    <br><span class="label">OBS:</span> {{ $d['destinatario']['obs'] }}
                @endif
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">CIDADE:</span> {{ $d['remetente']['endereco']['cidade'] ?? '' }} &nbsp;&nbsp; <span class="label">UF:</span> {{ $d['remetente']['endereco']['uf'] ?? '' }}
            </td>
            <td>
                <span class="label">CIDADE:</span> {{ $d['destinatario']['endereco']['cidade'] ?? '' }} &nbsp;&nbsp; <span class="label">UF:</span> {{ $d['destinatario']['endereco']['uf'] ?? '' }}
            </td>
        </tr>
        <tr>
            <td><span class="label">CEP:</span> {{ $d['remetente']['endereco']['cep'] ?? '' }} &nbsp;&nbsp; <span class="label">DOC:</span> {{ $d['remetente']['cpfCnpj'] ?? '' }}</td>
            <td><span class="label">CEP:</span> {{ $d['destinatario']['endereco']['cep'] ?? '' }} &nbsp;&nbsp; <span class="label">DOC:</span></td>
        </tr>
    </table>

    <div class="secao">IDENTIFICAÇÃO DOS BENS</div>
    <table>
        <tr class="cabecalho">
            <td style="width:8%">ITEM</td>
            <td>CONTEÚDO</td>
            <td style="width:15%">QUANT.</td>
            <td style="width:20%">VALOR</td>
        </tr>
        @php $totalQtd = 0; $totalValor = 0; @endphp
        @foreach(($d['itensDeclaracaoConteudo'] ?? []) as $i => $item)
            @php $totalQtd += (int) $item['quantidade']; $totalValor += (float) $item['valor']; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item['conteudo'] }}</td>
                <td>{{ $item['quantidade'] }}</td>
                <td>R$ {{ number_format((float) $item['valor'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="totais">
            <td colspan="2" style="text-align:right">TOTAIS</td>
            <td>{{ $totalQtd }}</td>
            <td>R$ {{ number_format($totalValor, 2, ',', '.') }}</td>
        </tr>
        <tr class="totais">
            <td colspan="3" style="text-align:right">PESO TOTAL (kg)</td>
            <td>{{ number_format((((int) ($d['pesoInformado'] ?? 0)) / 1000), 3, ',', '.') }}</td>
        </tr>
    </table>

    <div class="declaracao-texto">
        <div class="secao" style="margin:-10px -10px 8px -10px;">DECLARAÇÃO</div>
        Declaro que não me enquadro no conceito de contribuinte previsto no art. 4º da Lei Complementar nº 87/1996, uma vez que não realizo, com habitualidade ou em volume que caracterize intuito comercial, operações de circulação de mercadoria, ainda que se iniciem no exterior, ou estou dispensado da emissão da nota fiscal por força da legislação tributária vigente, responsabilizando-me, nos termos da lei e a quem de direito, por informações inverídicas.
        <br><br>
        Declaro que não envio objeto que ponha em risco o transporte aéreo, nem objeto proibido no fluxo postal, assumindo responsabilidade pela informação prestada, e ciente de que o descumprimento pode configurar crime, conforme artigo 261 do Código Penal Brasileiro. Declaro, ainda, estar ciente da lista de proibições e restrições, disponível no site dos Correios: https://www.correios.com.br/enviar/proibicoes-e-restricoes/proibicoes-e-restricoes.

        <div class="assinatura">
            {{ $d['remetente']['endereco']['cidade'] ?? '' }}, {{ \Carbon\Carbon::parse($d['dataHora'] ?? now())->format('d \d\e F \d\e Y') }}
            <br><br>
            _____________________________________________<br>
            Assinatura do Declarante/Remetente
        </div>
    </div>

    <div class="obs-fiscal">
        <strong>Chave NFe/Dce:</strong> {{ $d['chaveNFe'] ?? '' }}
        &nbsp;&nbsp; Observação: Constitui crime contra a ordem tributária suprimir ou reduzir tributo, ou contribuição social e qualquer acessório (Lei: 8.137/90 Art 1º, V)
        @if(!empty($d['chaveNFe']))
            <div style="margin-top:6px">{!! \DNS1D::getBarcodeHTML($d['chaveNFe'], 'C128', 1.5, 40) !!}</div>
        @else
            <div style="margin-top:6px; font-size:9px; color:#888;">(chave ainda não processada pela Correios — tente baixar novamente em alguns minutos)</div>
        @endif
    </div>
</body>
</html>