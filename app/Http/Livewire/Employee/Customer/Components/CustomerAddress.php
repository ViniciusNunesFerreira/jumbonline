<?php

namespace App\Http\Livewire\Employee\Customer\Components;

use App\Models\Visitante;
use App\Models\Detento;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

use Livewire\Component;

class CustomerAddress extends Component
{
    public Customer $customer;

   // public $visitantes = [];

    public Visitante $visitante;

    public $media = [];

    public $selected = [];

    public $html = '';

    public $paths = array();

    public bool $showAddressForm = false;

    public bool $showAddressesManageModal = false;

    protected $listeners = ['refresh' => '$refresh'];



    public function mount()
    {
        try{
            $visitante = $this->customer->visitantes->first();
            if(!empty($visitante)){
                $this->visitante =  $visitante;
            }
            
        }catch(\Exception $e){
            \Log::debug($e->getMessage());
        }
    }

    public function manageVisitantes()
    {
      //  $this->visitantes = $this->customer->visitantes->sortByDesc('created_at');

        $this->showAddressesManageModal = true;
    }


    public function view()
    {
        if ($this->showAddressesManageModal) $this->showAddressesManageModal = false;

        $this->showAddressForm = true;
    }

    public function save()
    {

        $media = $this->visitante->media()->whereIn('id', $this->selected)->get();

        $paths = array();

        $media->each( function($medium) use ($paths){
            if(!empty(optional($medium)->getPath())){
                array_push($this->paths, '<img class="carteirinha" width="500" src="'.$medium->getPath().'"/>');
            }
            
        });

        $imgs = '';
        
        foreach($this->paths as $path){
           $imgs .= $path.'<br>';
        };

        
        $this->html = '<!DOCTYPE html>
                        <html lang="pt-BR">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Fotos</title>
                            <style>
                                body{
                                    width: 29.7cm;
                                    max-width: 29.7cm;
                                    height: 21cm;
                                    max-height: 21cm;
                                    overflow: hidden;
                                }

                                .page{
                                    background-color: white;
                                    display: grid;
                                    margin 0 auto;
                                    overflow: hidden;
                                }

                                .carteirinha{
                                    max-width: 100%;
                                    object-fit: contain;
                                }

                            </style>
                        </head>
                        <body>
                            <div class="page">
                            '.$imgs.'
                            </div>
                        </body>
                        </html>';


             $filename = public_path().'/pdf/documentos_visitante_n'.$this->visitante->id.'visitante_'.$this->visitante->nome.'.pdf';
            if(file_exists($filename)){
                unlink($filename);
            }

            $fileurl =  explode('public',$filename);

            $url = url('/').$fileurl[1];

            Pdf::loadHTML($this->html)->save($filename)->stream('download.pdf');
    
            $this->showAddressForm = false;

            $this->reset('selected');

            $this->emitSelf('refresh');

        return redirect($url);
        
    }

    public function render()
    {
        return view('livewire.employee.customer.components.customer-address');
    }
}
