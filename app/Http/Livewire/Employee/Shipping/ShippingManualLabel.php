<?php

namespace App\Http\Livewire\Employee\Shipping;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class ShippingManualLabel extends Component
{
    // Estado do formulário de endereços
    public $state = [
        'remetente' => [
            'nome' => '',
            'doc' => '', // CPF/CNPJ opcional para declaração
            'endereco' => [
                'cep' => '',
                'logradouro' => '',
                'numero' => '',
                'complemento' => '',
                'bairro' => '',
                'cidade' => '',
                'uf' => '',
            ],
        ],
        'destinatario' => [
            'nome' => '',
            'doc' => '', // CPF/CNPJ opcional para declaração
            'obs' => '',
            'endereco' => [
                'cep' => '',
                'logradouro' => '',
                'numero' => '',
                'complemento' => '',
                'bairro' => '',
                'cidade' => '',
                'uf' => '',
            ]
        ],
    ];

    // Estado para lista de produtos
    public $products = [];
    
    // Produto temporário sendo adicionado
    public $tempProduct = [
        'name' => '',
        'qtd' => 1,
        'weight' => '', // em kg
        'value' => '',
    ];

    // Regras de validação
    protected $rules = [
        'state.remetente.nome' => 'required|string|min:3',
        'state.remetente.endereco.cep' => 'required|min:8',
        'state.remetente.endereco.logradouro' => 'required',
        'state.remetente.endereco.numero' => 'required',
        'state.remetente.endereco.bairro' => 'required',
        'state.remetente.endereco.cidade' => 'required',
        'state.remetente.endereco.uf' => 'required|max:2',

        'state.destinatario.nome' => 'required|string|min:3',
        'state.destinatario.endereco.cep' => 'required|min:8',
        'state.destinatario.endereco.logradouro' => 'required',
        'state.destinatario.endereco.numero' => 'required',
        'state.destinatario.endereco.bairro' => 'required',
        'state.destinatario.endereco.cidade' => 'required',
        'state.destinatario.endereco.uf' => 'required|max:2',
        
        'products' => 'required|array|min:1', // Obrigatório ter pelo menos 1 produto
    ];

    protected $messages = [
        'required' => 'Campo obrigatório',
        'min' => 'Mínimo de :min caracteres',
        'products.required' => 'Adicione pelo menos um produto à lista.',
        'products.min' => 'Adicione pelo menos um produto à lista.',
    ];

    public function searchCep($type)
    {
        $cep = preg_replace('/[^0-9]/', '', $this->state[$type]['endereco']['cep'] ?? '');

        if (strlen($cep) !== 8) {
            $this->addError("state.{$type}.endereco.cep", 'CEP inválido.');
            return;
        }

        try {
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->successful() && !isset($response['erro'])) {
                $data = $response->json();
                $this->state[$type]['endereco']['logradouro'] = $data['logradouro'] ?? '';
                $this->state[$type]['endereco']['bairro'] = $data['bairro'] ?? '';
                $this->state[$type]['endereco']['cidade'] = $data['localidade'] ?? '';
                $this->state[$type]['endereco']['uf'] = $data['uf'] ?? '';
                $this->resetErrorBag("state.{$type}.endereco.cep");
                session()->flash('success', "Endereço encontrado!");
            } else {
                $this->addError("state.{$type}.endereco.cep", 'CEP não encontrado.');
            }
        } catch (\Exception $e) {
            $this->addError("state.{$type}.endereco.cep", 'Erro na consulta.');
        }
    }

    public function addProduct()
    {
        $this->validate([
            'tempProduct.name' => 'required|string|min:2',
            'tempProduct.qtd' => 'required|integer|min:1',
            'tempProduct.weight' => 'required|numeric|min:0',
            'tempProduct.value' => 'required|numeric|min:0',
        ], [
            'tempProduct.name.required' => 'Informe o nome.',
            'tempProduct.qtd.required' => 'Informe a qtd.',
            'tempProduct.weight.required' => 'Informe o peso.',
            'tempProduct.value.required' => 'Informe o valor.',
        ]);

        $this->products[] = $this->tempProduct;

        // Resetar campos do produto
        $this->tempProduct = [
            'name' => '',
            'qtd' => 1,
            'weight' => '',
            'value' => '',
        ];
    }

    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products); // Reindexar array
    }

    public function geraEtiqueta()
    {
        $this->validate();

        // Gera HTML da Etiqueta
        $labelHtml = $this->getLabelHtml();
        
        // Gera HTML da Declaração de Conteúdo
        $declarationHtml = $this->getDeclarationHtml();

        // Combina os dois HTMLs
        // Importante: O style de page-break está dentro dos métodos ou no CSS global do PDF
        $finalHtml = $labelHtml . $declarationHtml . '</body></html>';

        $fileName = 'etiqueta_declaracao_' . time() . '.pdf';

        return response()->streamDownload(function () use ($finalHtml) {
            echo Pdf::loadHTML($finalHtml)->setPaper('a4')->output();
        }, $fileName);
    }

    private function getCommonCss()
    {
        return '
            body { font-family: sans-serif; width: 100%; margin: 0; padding: 0; }
            .page-break { page-break-before: always; }
            table { width: 100%; border-collapse: collapse; }
        ';
    }

    private function getLabelHtml()
    {
        $cepDestino = preg_replace("/[^0-9]/", "", $this->state["destinatario"]["endereco"]["cep"]);
        $barcode = class_exists('\DNS1D') 
            ? \DNS1D::getBarcodeHTML($cepDestino, 'EAN5') 
            : '<div style="border:1px solid #000;">CODE</div>';

        return '<!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <style>
                ' . $this->getCommonCss() . '
                .label-page { width: 10cm; height: 15cm; border: 1px dashed #ccc; margin: 0 auto; position: relative; overflow: hidden; }
                .destinatario { width: 100%; height: 3cm; margin-top: 8.6cm; padding: 5px 10px; font-size: 11px; line-height: 13px; }
                .remetente { margin-top: 0.5cm; width: 100%; height: 3cm; padding: 5px 10px; font-size: 11px; line-height: 13px; }
                address { text-transform: uppercase; font-weight: bold; font-style: normal; }
            </style>
        </head>
        <body>
            <div class="label-page">
                <div class="destinatario">
                    <address>
                        DESTINATÁRIO: '.$this->state["destinatario"]["nome"].'<br>
                        '.$this->state["destinatario"]["endereco"]["logradouro"].', '.$this->state["destinatario"]["endereco"]["numero"].'<br>
                        '.$this->state["destinatario"]["endereco"]["bairro"].' <br> 
                        <strong>'.$this->state["destinatario"]["endereco"]["cep"].'</strong> &nbsp;&nbsp; 
                        '.$this->state["destinatario"]["endereco"]["cidade"].'-'.$this->state["destinatario"]["endereco"]["uf"].'
                    </address>
                    <table style="width:100%; margin-top: 5px;">
                        <tr>
                            <td style="width:50%">'.$barcode.'</td> 
                            <td style="width:50%; font-size:10px;">Obs: '.$this->state["destinatario"]["obs"].'</td>
                        </tr>
                    </table>
                </div>
                <div class="remetente">
                    <address>
                    REMETENTE: '.$this->state["remetente"]["nome"].' <br>
                    '.$this->state["remetente"]["endereco"]["logradouro"].', '.$this->state["remetente"]["endereco"]["numero"].'<br>
                    '.$this->state["remetente"]["endereco"]["bairro"].' <br> <br>
                    '.$this->state["remetente"]["endereco"]["cep"].' &nbsp;&nbsp; 
                    '.$this->state["remetente"]["endereco"]["cidade"].'-'.$this->state["remetente"]["endereco"]["uf"].'
                    </address>
                </div>
            </div>'; // Não fecha body/html aqui pois virá a declaração
    }

    private function getDeclarationHtml()
    {
        // Cálculos de totais
        $totalQtd = 0;
        $totalPeso = 0;
        $totalValor = 0;

        $rows = '';
        foreach($this->products as $index => $item) {
            $totalQtd += $item['qtd'];
            $totalPeso += ($item['weight'] * $item['qtd']);
            $totalValor += ($item['value'] * $item['qtd']);
            
            $rows .= '
            <tr>
                <td>'.($index + 1).'</td>
                <td>'.$item['name'].'</td>
                <td align="center">'.$item['qtd'].'</td>
                <td align="right">'.number_format($item['weight'], 3, ',', '.').'</td>
                <td align="right">R$ '.number_format($item['value'], 2, ',', '.').'</td>
            </tr>';
        }

        return '
        <div class="page-break"></div>
        <div style="padding: 20px; font-family: Arial, sans-serif;">
            <h3 style="text-align: center; margin-bottom: 5px;">DECLARAÇÃO DE CONTEÚDO</h3>
            <p style="text-align: center; font-size: 10px; margin-top:0;">(Conforme Art. 13 da Portaria nº 32/2020)</p>
            
            <table border="1" cellpadding="5" style="font-size: 11px; margin-bottom: 10px;">
                <tr style="background-color: #eee;">
                    <td width="50%"><strong>REMETENTE</strong></td>
                    <td width="50%"><strong>DESTINATÁRIO</strong></td>
                </tr>
                <tr>
                    <td valign="top">
                        NOME: '.$this->state['remetente']['nome'].'<br>
                        ENDEREÇO: '.$this->state['remetente']['endereco']['logradouro'].', '.$this->state['remetente']['endereco']['numero'].'<br>
                        CIDADE/UF: '.$this->state['remetente']['endereco']['cidade'].'/'.$this->state['remetente']['endereco']['uf'].'<br>
                        CEP: '.$this->state['remetente']['endereco']['cep'].' &nbsp; CPF/CNPJ: _______________
                    </td>
                    <td valign="top">
                        NOME: '.$this->state['destinatario']['nome'].'<br>
                        ENDEREÇO: '.$this->state['destinatario']['endereco']['logradouro'].', '.$this->state['destinatario']['endereco']['numero'].'<br>
                        CIDADE/UF: '.$this->state['destinatario']['endereco']['cidade'].'/'.$this->state['destinatario']['endereco']['uf'].'<br>
                        CEP: '.$this->state['destinatario']['endereco']['cep'].' &nbsp; CPF/CNPJ: _______________
                    </td>
                </tr>
            </table>

            <table border="1" cellpadding="5" style="font-size: 11px;">
                <tr style="background-color: #eee; text-align: center;">
                    <td width="5%">ITEM</td>
                    <td width="55%">CONTEÚDO</td>
                    <td width="10%">QTD</td>
                    <td width="15%">PESO (Kg)</td>
                    <td width="15%">VALOR (R$)</td>
                </tr>
                '.$rows.'
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="2" align="right">TOTAIS</td>
                    <td align="center">'.$totalQtd.'</td>
                    <td align="right">'.number_format($totalPeso, 3, ',', '.').'</td>
                    <td align="right">R$ '.number_format($totalValor, 2, ',', '.').'</td>
                </tr>
            </table>

            <br>
            <div style="font-size: 10px; text-align: justify;">
                <strong>DECLARAÇÃO:</strong> Declaro que não me enquadro no conceito de contribuinte habitual do ICMS, razão pela qual não possuo Inscrição Estadual. Declaro ainda que a remessa não configura operação mercantil e que o conteúdo da encomenda segue as normas de segurança.
            </div>
            
            <br><br>
            <div style="text-align: center;">
                _______________________________________________________<br>
                Assinatura do Declarante
            </div>
            <div style="text-align: center; margin-top: 5px; font-size: 10px;">
                Data: '.date('d/m/Y').'
            </div>
        </div>';
    }

    public function render()
    {
        return view('livewire.employee.shipping.shipping-manual-label')
            ->layout('layouts.admin');
    }
}