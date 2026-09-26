<?php

namespace App\Http\Livewire\Employee\Customer;

use App\Models\Address;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CustomerCreate extends Component
{
    public Customer $customer;

    public Address $address;

    public $countries = [];

    public $customer_country;

    public $customer_phone;

    public $customer_phone_number;

    public $customer_phone_country;

    public $customer_password;

    public $customer_password_confirmation;

    public $address_country;

    public $address_phone;

    public $address_phone_number;

    public $address_phone_country;

    /**
     * Ligado por padrão — a grande maioria dos cadastros usa o mesmo
     * telefone pro cliente e pro endereço, então evita pedir o mesmo dado
     * duas vezes. Só desmarca quem realmente precisa de um número diferente.
     */
    public bool $sameAddressPhone = true;

    protected function rules()
    {
        return [
            'customer.name' => ['required', 'string'],
            'customer.email' => ['required', 'email', 'unique:customers,email'],
            'customer_password' => ['required', 'string', 'min:8', 'confirmed'],
            'customer_phone' => [
                'nullable',
                Rule::phone()->countryField('phone_country'),
                Rule::unique('customers', 'phone'),
            ],
            'customer_phone_country' => ['nullable', 'string', 'exists:countries,iso2'],
            'customer.notes' => ['nullable', 'string'],
            'address.country_id' => ['required', 'exists:countries,id'],
            'address.name' => ['required', 'string', 'max:255'],
            'address.company_name' => ['nullable', 'string', 'max:255'],
            'address.address_line_1' => ['required', 'string', 'max:255'],
            'address.address_line_2' => ['nullable', 'string', 'max:255'],
            'address.city' => ['required', 'string', 'max:255'],
            'address.state' => ['nullable', 'string', 'max:255'],
            'address.postcode' => ['nullable', 'string', 'max:255'],
            'address_phone' => ['nullable', Rule::phone()->countryField('phone_country')],
            'address_phone_country' => ['nullable', 'string', 'exists:countries,iso2'],
            'address.is_default' => ['boolean'],
        ];
    }

    protected $messages = [
        'customer_phone.unique' => 'Este telefone já está cadastrado em outro cliente.',
    ];

    public function mount()
    {
        $this->customer = new Customer();

        $this->address = new Address(['is_default' => true]);

        $this->countries = Country::query()->select(['id', 'name', 'iso2', 'phonecode', 'emoji'])->orderBy('name')->get();

        // Padrão Brasil — praticamente 100% dos clientes cadastrados são
        // daqui; antes vinha "US" por padrão, obrigando corrigir na mão
        // em todo cadastro.
        $this->selectCustomerCountry($this->countries->where('iso2', 'BR')->first()->iso2);

        $this->selectAddressCountry($this->countries->where('iso2', 'BR')->first()->iso2);
    }

    public function selectCustomerCountry($value)
    {
        $this->customer_country = $this->countries->where('iso2', $value)->first();

        $this->customer_phone_country = $value;
    }

    public function selectAddressCountry($value)
    {
        $this->address_country = $this->countries->where('iso2', $value)->first();

        $this->address_phone_country = $value;
    }

    public function updatedAddressCountryId($value)
    {
        $this->selectAddressCountry($this->countries->where('id', $value)->first()->iso2);
    }

    public function save()
    {
        $this->customer_phone = $this->buildE164($this->customer_phone_number, $this->customer_country);

        if (! $this->sameAddressPhone) {
            $this->address_phone = $this->buildE164($this->address_phone_number, $this->address_country);
        }

        $this->validate();

        $this->customer->password = bcrypt($this->customer_password);

        $this->customer->phone_country = $this->customer_phone_country;

        $this->customer->phone = $this->customer_phone;

        try {
            $this->customer->save();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->addError('customer_phone', 'Este telefone já está cadastrado em outro cliente.');
                return;
            }

            throw $e;
        }

        if ($this->sameAddressPhone) {
            $this->address->phone_country = $this->customer_phone_country;
            $this->address->phone = $this->customer_phone;
        } else {
            $this->address->phone_country = $this->address_phone_country;
            $this->address->phone = $this->address_phone;
        }

        $this->address->addressable()->associate($this->customer);

        $this->address->save();

        $this->redirect(route('employee.customers.detail', $this->customer));
    }

    protected function buildE164(?string $localNumber, $country): ?string
    {
        $digits = preg_replace('/\D/', '', $localNumber ?? '');

        return $digits ? '+' . $country->phonecode . $digits : null;
    }

    public function render()
    {
        return view('livewire.employee.customer.customer-create')->layout('layouts.admin');
    }
}