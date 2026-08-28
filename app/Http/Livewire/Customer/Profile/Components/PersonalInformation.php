<?php

namespace App\Http\Livewire\Customer\Profile\Components;

use Illuminate\Validation\Rule;
use Livewire\Component;

class PersonalInformation extends Component
{
    public $state = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'phone_country' => '',
    ];

    protected $messages = [
        'state.name.required' => 'O nome é obrigatório.',
        'state.email.required' => 'O e-mail é obrigatório.',
        'state.email.email' => 'Informe um e-mail válido.',
        'state.email.unique' => 'Esse e-mail já está em uso.',
        'state.phone.required' => 'O telefone é obrigatório.',
    ];

    public function mount()
    {
        $this->state = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'phone_country' => $this->user->phone_country ?: 'BR',
        ];
    }

    public function save()
    {
        $this->validate([
            'state.name' => ['required'],
            'state.email' => ['required', 'email', Rule::unique('customers', 'email')->ignore($this->user->id)],
            'state.phone' => ['required'],
            'state.phone_country' => ['required'],
        ]);

        // phone_country primeiro — mesma lição do cast de telefone que já aprendemos
        $this->user->update([
            'phone_country' => $this->state['phone_country'],
            'phone' => $this->state['phone'],
            'name' => $this->state['name'],
            'email' => $this->state['email'],
        ]);

        $this->notify(trans('Perfil atualizado com sucesso.'));
    }

    public function getUserProperty()
    {
        return \Auth::user();
    }

    public function render()
    {
        return view('livewire.customer.profile.components.personal-information');
    }
}