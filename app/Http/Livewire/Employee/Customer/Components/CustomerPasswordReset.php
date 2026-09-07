<?php

namespace App\Http\Livewire\Employee\Customer\Components;

use App\Enums\InteractionChannel;
use App\Enums\InteractionType;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CustomerPasswordReset extends Component
{
    public Customer $customer;

    public bool $showConfirm = false;

    public bool $showReveal = false;

    public string $channel = '';

    public ?string $generatedPassword = null;

    protected function rules()
    {
        return [
            'channel' => ['required', 'in:' . collect(InteractionChannel::cases())->map->name->implode(',')],
        ];
    }

    protected function messages()
    {
        return [
            'channel.required' => __('Selecione como a senha será repassada ao cliente.'),
        ];
    }

    public function getChannelsProperty()
    {
        return InteractionChannel::cases();
    }

    public function openConfirm()
    {
        $this->channel = '';
        $this->resetErrorBag();
        $this->showConfirm = true;
    }

    public function generate()
    {
        $this->validate();

        $temporaryPassword = $this->generateTemporaryPassword();

        $this->customer->forceFill([
            'password' => Hash::make($temporaryPassword),
            'must_change_password' => true,
        ])->save();

        $this->customer->interactions()->create([
            'employee_id' => Auth::guard('employee')->id(),
            'channel' => constant(InteractionChannel::class . '::' . $this->channel),
            'type' => InteractionType::RESET_SENHA,
            'description' => __('Senha temporária gerada pelo atendente. O cliente será obrigado a defini-la novamente no próximo login.'),
        ]);

        $this->generatedPassword = $temporaryPassword;

        $this->showConfirm = false;

        $this->showReveal = true;

        $this->emit('refresh');
    }

    public function closeReveal()
    {
        $this->generatedPassword = null;

        $this->showReveal = false;
    }

    /**
     * 10 caracteres, sem 0/O/1/l/I para não confundir na hora de ditar por
     * telefone. Garante maiúscula + minúscula + número na composição para
     * já nascer compatível com Password::defaults() (AppServiceProvider) —
     * se essa regra mudar (ex.: exigir símbolo), este gerador precisa
     * acompanhar.
     */
    protected function generateTemporaryPassword(): string
    {
        $upper = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        $lower = 'abcdefghijkmnpqrstuvwxyz';
        $numbers = '23456789';

        $password = $upper[random_int(0, strlen($upper) - 1)]
            . $lower[random_int(0, strlen($lower) - 1)]
            . $numbers[random_int(0, strlen($numbers) - 1)];

        $all = $upper . $lower . $numbers;

        for ($i = 0; $i < 7; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        return str_shuffle($password);
    }

    public function render()
    {
        return view('livewire.employee.customer.components.customer-password-reset');
    }
}