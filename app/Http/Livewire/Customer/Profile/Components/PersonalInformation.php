<?php

namespace App\Http\Livewire\Customer\Profile\Components;

use Illuminate\Validation\Rule;
use Livewire\Component;

class PersonalInformation extends Component
{
    public $state = [
        'name' => '',
        'email' => '',
    ];

    protected $messages = [
        'state.name.required' => 'The name field is required.',
        'state.email.required' => 'The email field is required.',
        'state.email.email' => 'The email must be a valid email address.',
        'state.email.unique' => 'The email has already been taken.',
    ];

    public function mount()
    {
        $this->state = [
            'name' => $this->user->name,
            'email' => $this->user->email,
        ];
    }

    public function save()
    {
        $this->validate([
            'state.name' => ['required'],
            'state.email' => ['required', 'email', Rule::unique('customers', 'email')->ignore($this->user->id)],
        ]);

        $this->user->update([
            'name' => $this->state['name'],
            'email' => $this->state['email'],
        ]);

        $this->notify(trans('Your profile has been updated.'));
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
