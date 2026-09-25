<?php

namespace App\Http\Livewire\Employee\Customer\Components;

use App\Models\Country;
use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CustomerInformation extends Component
{
    public Customer $customer;

    public Country $country;

    public $countries = [];

    /**
     * Número completo em E164 (com código do país) — só existe pra
     * validação/salvamento. A digitação do usuário nunca toca aqui
     * diretamente; veja $phoneNumber.
     */
    public $phone;

    /**
     * Só o número local, sem código de país — é isso que o campo de
     * texto mostra e o usuário edita de verdade. O código do país só
     * muda pelo seletor (selectCountry), nunca por aqui.
     */
    public $phoneNumber;

    public $phone_country;

    public bool $isEditing = false;

    protected function rules()
    {
        return [
            'customer.name' => ['required', 'string'],
            'customer.email' => ['required', 'email'],
            'phone' => [
                'nullable',
                Rule::phone()->countryField('phone_country'),
                Rule::unique('customers', 'phone')->ignore($this->customer->id),
            ],
            'phone_country' => ['nullable', 'string', 'exists:countries,iso2'],
        ];
    }

    protected $messages = [
        'phone.unique' => 'Este telefone já está cadastrado em outro cliente.',
    ];

    public function mount()
    {
        $this->country = $this->customer->phone_country ? Country::where('iso2', $this->customer->phone_country)->first() : Country::where('iso2', 'US')->first();

        $this->phone_country = $this->country->iso2;

        $fullNumber = $this->customer->phone ? $this->customer->phone->formatE164() : '+' . $this->country->phonecode;

        $this->phoneNumber = Str::after($fullNumber, '+' . $this->country->phonecode);

        $this->phone = $fullNumber;
    }

    /**
     * Troca só o país — o número que a pessoa já digitou fica intacto.
     */
    public function selectCountry($value)
    {
        $this->country = $this->countries->where('iso2', $value)->first();

        $this->phone_country = $value;
    }

    public function edit()
    {
        $this->countries = Country::query()->select(['id', 'name', 'iso2', 'phonecode', 'emoji'])->orderBy('name')->get();

        $this->isEditing = true;
    }

    public function save()
    {
        // Monta o E164 completo só agora, a partir do país atual + só os
        // dígitos do número local — nunca confia em caractere estranho
        // que possa ter passado pelo filtro do navegador.
        $digitsOnly = preg_replace('/\D/', '', $this->phoneNumber ?? '');

        $this->phone = $digitsOnly ? '+' . $this->country->phonecode . $digitsOnly : null;

        $this->validate();

        $this->customer->phone_country = $this->phone_country;

        $this->customer->phone = $this->phone;

        try {
            $this->customer->save();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->addError('phone', 'Este telefone já está cadastrado em outro cliente.');
                return;
            }

            throw $e;
        }

        $this->isEditing = false;

        $this->emitUp('refresh');
    }

    public function render()
    {
        return view('livewire.employee.customer.components.customer-information');
    }
}