<?php

namespace App\Http\Livewire\Employee\Customer\Components;

use App\Enums\InteractionChannel;
use App\Enums\InteractionType;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CustomerInteractions extends Component
{
    public Customer $customer;

    public bool $showForm = false;

    public string $channel = '';

    public string $type = '';

    public string $description = '';

    protected $listeners = ['refresh' => '$refresh'];

    protected function rules()
    {
        return [
            'channel' => ['required', 'in:' . collect(InteractionChannel::cases())->map->name->implode(',')],
            'type' => ['required', 'in:' . collect(InteractionType::cases())->map->name->implode(',')],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }

    protected function messages()
    {
        return [
            'channel.required' => __('Selecione o canal do contato.'),
            'type.required' => __('Selecione o tipo de registro.'),
            'description.required' => __('Descreva o que foi tratado.'),
        ];
    }

    public function getChannelsProperty()
    {
        return InteractionChannel::cases();
    }

    public function getTypesProperty()
    {
        return InteractionType::cases();
    }

    public function getInteractionsProperty()
    {
        return $this->customer->interactions()->with('employee')->latest()->limit(30)->get();
    }

    public function openForm()
    {
        $this->reset(['channel', 'type', 'description']);
        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        // Enums puros (sem backing) não têm ::from() — resolvo a instância pelo
        // nome do case já validado acima, seguindo o padrão do resto do app de
        // sempre gravar a instância do enum, nunca a string crua.
        $this->customer->interactions()->create([
            'employee_id' => Auth::guard('employee')->id(),
            'channel' => constant(InteractionChannel::class . '::' . $this->channel),
            'type' => constant(InteractionType::class . '::' . $this->type),
            'description' => $this->description,
        ]);

        $this->reset(['channel', 'type', 'description']);

        $this->showForm = false;

        $this->notify(__('Interação registrada.'));
    }

    public function render()
    {
        return view('livewire.employee.customer.components.customer-interactions');
    }
}