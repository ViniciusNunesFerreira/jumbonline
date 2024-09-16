<?php

namespace App\Http\Livewire\Guest\PurchaseComponents;

use Livewire\Component;

use App\Models\Customer;
use App\Models\Detento;
use App\Models\Visitante;
use Illuminate\Support\Facades\Http;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CadastroDetento extends Component
{

    use WithFileUploads;

    public $image;

    public $selected = [];

    public bool $confirmingMediaDeletion = false;

    public $prison;

    public $data = array( 
            'detento' => ['name' => '', 'matricula' => '','raio' => '','cela' => ''],
            'visitante' => ['nome' => '', 'logradouro' => '', 'numero' => '', 'bairro' => '','cidade' => '', 'uf' => '', 'cep' => '']
        );
    /* Função para cadastrar multiplo detentos */
        /*
        public function getAvailableDetentosProperty(): \Illuminate\Database\Eloquent\Collection|array
        {
            return $this->customer ?
            Detento::query()->where('customer_id', $this->customer?->id)->get() : collect([]);
        }
        */
    /* Fim função de listagem dos detentos */

    protected $listeners = ['refresh' => '$refresh', 'changeTab', 'upload:finished' => 'saveImage'];

    public function mount($prison)
    {
        $this->prison = $prison;

       

        $this->data['detento'] = [
            'name' => $this->detento->name,
            'matricula' => $this->detento->matricula,
            'raio' => $this->detento->raio,
            'cela' => $this->detento->cela
        ];

        $this->data['visitante'] = [
            'nome' => $this->visitante->nome,
            'logradouro' => $this->visitante->logradouro,
            'numero' => $this->visitante->numero,
            'bairro' => $this->visitante->bairro,
            'cidade' => $this->visitante->cidade,
            'uf' => $this->visitante->uf,
            'cep' => $this->visitante->cep
        ];
        

    }

    protected $rules = [
        'data.detento.name' => ['required'],
        'data.detento.matricula' => ['required'],
        'data.detento.raio' => ['required'],
        'data.detento.cela' => ['required'],
        'data.visitante.nome' => ['required'],
        'data.visitante.logradouro' => ['required'],
        'data.visitante.numero' => ['required'],
        'data.visitante.bairro' => ['required'],
        'data.visitante.cidade' => ['required'],
        'data.visitante.uf' => ['required'],
        'data.visitante.cep' => ['required']
    ];

    protected function messages(): array
    {
        return [
            'data.detento.name.required' => ['Informe nome completo do detento'],
            'data.detento.matricula.required' => ['campo matricula é obrigatório'],
            'data.detento.raio.required' => ['campo raio é obrigatório'],
            'data.detento.cela.required' => ['campo cela é obrigatório'],
            'data.visitante.nome.required' => ['Informe o nome do visitante'],
            'data.visitante.logradouro.required' => ['logradouro obrigatório'],
            'data.visitante.numero.required' => ['numero obrigatório'],
            'data.visitante.bairro.required' => ['bairro é obrigatório'],
            'data.visitante.cidade.required' => ['cidade é obrigatório'],
            'data.visitante.uf.required' => ['uf é obrigatório'],
            'data.visitante.cep.required' => ['cep é obrigatório']
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function getCustomerProperty(): \App\Models\Customer|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return \Auth::user();
    }

    public function getDetentoProperty(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {

        return  Detento::where('customer_id', $this->customer->id)->firstOrNew( 
            ['customer_id' => $this->customer->id ]
        );

    }

    public function getVisitanteProperty(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {

        return Visitante::query()->firstOrCreate(['customer_id' => $this->customer->id ], 
                ['prison_unit_id' => $this->prison->id]
            );

    }

    public function changeDataVisitanteCep()
    {
        $cep = trim( preg_replace("/[^0-9]/", "", $this->data['visitante']['cep']));

        $result = Http::get("http://viacep.com.br/ws/{$cep}/json/");

        if(!empty($result['logradouro'])){
            $this->data['visitante']['logradouro'] = $result['logradouro'];
            $this->data['visitante']['bairro'] = $result['bairro'];
            $this->data['visitante']['cidade'] = $result['localidade'];
            $this->data['visitante']['uf'] = $result['uf'];
        }

    }

    
    public function saveData()
    {

        $this->validate([
            'data.detento.name' => ['required'],
            'data.detento.matricula' => ['required'],
            'data.detento.raio' => ['required'],
            'data.detento.cela' => ['required'],
            'data.visitante.nome' => ['required'],
            'data.visitante.logradouro' => ['required'],
            'data.visitante.numero' => ['required'],
            'data.visitante.bairro' => ['required'],
            'data.visitante.cidade' => ['required'],
            'data.visitante.uf' => ['required'],
            'data.visitante.cep' => ['required']
        ]);

        
        $this->detento->name = $this->data['detento']['name'];
        $this->detento->matricula = $this->data['detento']['matricula'];
        $this->detento->raio = $this->data['detento']['raio'];
        $this->detento->cela = $this->data['detento']['cela'];
        $this->detento->prison_unit_id = $this->prison->id;
        $this->detento->save();
       

        $this->visitante->update($this->data['visitante']);
        
        $this->emitUp('changeTab', 'tabs-pagamento');

    }

    public function saveImage()
    {
        $this->validate([
            'image' => 'file|image|max:5120',
        ]);

        try {
            $this->visitante
                ->addMedia($this->image->getRealPath())
                ->usingName(pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME))
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('cover');
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            $this->notify($e->getMessage());
        }

        $this->reset('image');

        $this->emit('refresh')->self();
    }

    public function deleteImage()
    {
        $this->visitante->getFirstMedia('cover')->delete();

        $this->emit('refresh')->self();
    }



    public function render()
    {
        return view('livewire.guest.purchase-components.cadastro-detento', ['visitante' => $this->visitante, 'detento' => $this->detento]);
    }
}
