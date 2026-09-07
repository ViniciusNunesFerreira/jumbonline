<?php

namespace App\Http\Livewire\Customer\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class TemporaryPasswordChange extends Component
{
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    protected function rules()
    {
        return [
            'state.current_password' => ['required', 'current_password:customer'],
            'state.password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    protected $messages = [
        'state.current_password.required' => 'A senha temporária é obrigatória.',
        'state.current_password.current_password' => 'A senha temporária informada está incorreta.',
        'state.password.required' => 'Defina uma nova senha.',
        'state.password.confirmed' => 'A confirmação de senha não confere.',
        'state.password.min' => 'A nova senha precisa ter pelo menos 8 caracteres.',
    ];

    public function save()
    {
        $this->validate();

        Auth::guard('customer')->user()->update([
            'password' => Hash::make($this->state['password']),
            'must_change_password' => false,
        ]);

        $this->redirect(session()->pull('url.intended', route('customer.dashboard')));
    }

    public function render()
    {
        return view('livewire.customer.auth.temporary-password-change')->layout('layouts.auth');
    }
}