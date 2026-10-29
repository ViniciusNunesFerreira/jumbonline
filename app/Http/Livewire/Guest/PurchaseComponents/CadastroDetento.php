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

    /**
     * Siglas válidas: a UF do visitante vai crua para o snapshot do pedido e
     * para o remetente da pré-postagem dos Correios.
     */
    private const UFS = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];

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

    protected $listeners = ['refresh' => '$refresh', 'upload:finished' => 'saveImage'];

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

    /**
     * Fonte ÚNICA das regras: usada no blur (updated → validateOnly) e no
     * saveData(). Limites espelham as colunas de detentos/visitantes
     * (string = 255; numero/cep = 10; uf = 2 letras válidas).
     */
    protected function rules(): array
    {
        return [
            'data.detento.name' => ['required', 'max:255'],
            'data.detento.matricula' => ['required', 'max:255'],
            'data.detento.raio' => ['required', 'max:255'],
            'data.detento.cela' => ['required', 'max:255'],
            'data.visitante.nome' => ['required', 'max:255'],
            'data.visitante.logradouro' => ['required', 'max:255'],
            'data.visitante.numero' => ['required', 'max:10'],
            'data.visitante.bairro' => ['required', 'max:255'],
            'data.visitante.cidade' => ['required', 'max:255'],
            'data.visitante.uf' => ['bail', 'required', 'size:2', 'in:' . implode(',', self::UFS)],
            'data.visitante.cep' => ['required', 'regex:/^\d{2}\.?\d{3}-?\d{3}$/'],
        ];
    }

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
            'data.visitante.cep.required' => ['cep é obrigatório'],

            // ATENÇÃO: mensagens de regras de tamanho (size, max, min, between...)
            // precisam ser STRING. Em array, o Laravel procura a chave do tipo
            // ('string', 'numeric'...) e lança "Undefined array key".
            'data.visitante.uf.size' => 'Informe a UF com 2 letras (ex.: SP)',
            'data.visitante.uf.in' => 'UF inválida. Use a sigla do estado (ex.: SP)',
            'data.visitante.numero.max' => 'O número aceita no máximo 10 caracteres. Coloque o complemento junto ao logradouro.',
            'data.visitante.cep.regex' => 'CEP inválido. Use o formato 00000-000.',
            // Genérica: precisa vir DEPOIS das específicas (a primeira que casar vence).
            'data.*.*.max' => 'Este campo aceita no máximo :max caracteres.',
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'data.visitante.uf') {
            $this->data['visitante']['uf'] = mb_strtoupper(trim((string) $this->data['visitante']['uf']));
        }

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

        if (empty($this->prison?->id) && session()->has('prison')) {
            $unidade = \App\Models\PrisonUnit::query()->where('slug', session('prison'))->first();

            if ($unidade) {
                $this->prison = $unidade;
            }
        }

        if (empty($this->prison?->id)) {
            $this->notify('Selecione a unidade prisional na etapa anterior antes de continuar.');
            return;
        }

        $this->data['visitante']['uf'] = mb_strtoupper(trim((string) $this->data['visitante']['uf']));

        $this->validate();

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
            'image' => 'mimes:jpeg,bmp,png|max:5120',
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