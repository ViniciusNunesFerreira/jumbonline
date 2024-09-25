<?php

namespace App\Http\Livewire\Employee\Shipping;

use Livewire\Component;

use App\Http\Livewire\Traits\Correios;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;


class ShippingPrePost extends Component
{
    use Correios;

    public Order $order;

    public $html = '';

    public $state = [

        'remetente' => [
                'nome' => '',
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
        'destinatario'  => [
            'nome' => '',
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

    public function mount()
    {
        $this->state['remetente'] = [
                'nome' => $this->order->visitante->nome,
                'endereco' => [
                    'cep' => $this->order->visitante->cep,
                    'logradouro' => $this->order->visitante->logradouro,
                    'numero' => $this->order->visitante->numero,
                    'complemento' => '',
                    'bairro' => $this->order->visitante->bairro,
                    'cidade' => $this->order->visitante->cidade,
                    'uf' => $this->order->visitante->uf,
                ]
        ];

        $this->state['destinatario'] = [
                'nome' => $this->order->detento->name,
                'obs' => $this->order->detento->matricula.' '.$this->order->detento->raio.' '.$this->order->detento->cela,
                'endereco' => [
                    'cep' => $this->order->prison_unit->cep,
                    'logradouro' => $this->order->prison_unit->logradouro,
                    'numero' => $this->order->prison_unit->numero,
                    'complemento' => '',
                    'bairro' => $this->order->prison_unit->bairro,
                    'cidade' => $this->order->prison_unit->cidade,
                    'uf' => $this->order->prison_unit->uf,
                ]
        ];


        $this->html = '<!DOCTYPE html>
                        <html lang="pt-BR">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Etiqueta Correios</title>
                            <style>
                                body{
                                    width: 29.7cm;
                                    max-width: 29.7cm;
                                    height: 21cm;
                                    max-height: 21cm;
                                    overflow: hidden;
                                }

                                .page{
                                    width: 10cm;
                                    height: 15cm;
                                    background-color: white;
                                    display: grid;
                                    margin 0 auto;
                                    background-image: url("img/bg-etiqueta.png");
                                    background-repeat: no-repeat;
                                    background-position: center; /* Center the image */
                                    background-size: cover;
                                     overflow: hidden;
                                }

                                .label{
                                    width: 10cm;
                                    height: 15cm;
                                }

                                .destinatario{
                                    width: 100%;
                                    height: 3cm;
                                    margin-top: 8.6cm;
                                    padding: 0.10cm  0.30cm ;
                                    font-size: 11px;
                                    line-height: 13px;
                                    overflow: hidden;
                                }

                                .remetente{
                                    margin-top: 0.5cm;
                                    width: 100%;
                                    height: 3cm;
                                    padding: 0.10cm  0.30cm ;
                                    font-size: 11px;
                                    line-height: 13px;
                                    overflow: hidden;
                                }
                                address{
                                    text-transform: uppercase;
                                    font-weight: bold;
                                }

                                .cb-dest{
                                    display:grid;
                                    
                                }
                                .cb{
                                    height: 1cm;
                                    max-height: 1cm;
                                    overflow: hidden;
                                    width:50%;
                                }
                                .obs{
                                    width:50%;
                                }

                            </style>
                        </head>
                        <body>
                            <div class="page">
                                <div class="label">
                                    <div class="destinatario">
                                        <address>
                                            '.$this->state["destinatario"]["nome"].'<br>
                                            '.$this->state["destinatario"]["endereco"]["logradouro"].', '.$this->state["destinatario"]["endereco"]["numero"].'<br>
                                            '.$this->state["destinatario"]["endereco"]["bairro"].' <br> <bold>
                                            '.$this->state["destinatario"]["endereco"]["cep"].'</bold>  &nbsp;&nbsp; 
                                            '.$this->state["destinatario"]["endereco"]["cidade"].'-'.$this->state["destinatario"]["endereco"]["uf"].'
                                        </address>
                                        <table style="width:100%">
                                            <tr>
                                                <td style="width:50%" class="cb">'.\DNS1D::getBarcodeHTML(preg_replace("/[^0-9]/", "", $this->state["destinatario"]["endereco"]["cep"]), 'EAN5').' </td> 
                                                <td>'.$this->state["destinatario"]["obs"].'</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="remetente">
                                        <address>
                                        '.$this->state["remetente"]["nome"].' <br>
                                        '.$this->state["remetente"]["endereco"]["logradouro"].', '.$this->state["remetente"]["endereco"]["numero"].'<br>
                                        '.$this->state["remetente"]["endereco"]["bairro"].' <br> <br>
                                        '.$this->state["remetente"]["endereco"]["cep"].' &nbsp;&nbsp; 
                                        '.$this->state["remetente"]["endereco"]["cidade"].'-'.$this->state["remetente"]["endereco"]["uf"].'
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </body>
                        </html>';


                    

    }

    public function geraEtiqueta()
    {
       
        $filename = public_path().'/pdf/etiqueta_order_n'.$this->order->id.'user_'.$this->order->customer->id.'.pdf';
        if(file_exists($filename)){
            unlink($filename);
        }

        $fileurl =  explode('public',$filename);

        $url = url('/').$fileurl[1];

        Pdf::loadHTML($this->html)->save($filename)->stream('download.pdf');
    
        return redirect($url);
    
    }

    public function render()
    {
        return view('livewire.employee.shipping.shipping-pre-post')->layout('layouts.admin');
    }
}
