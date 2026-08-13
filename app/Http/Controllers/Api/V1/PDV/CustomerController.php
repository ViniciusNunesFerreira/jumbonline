<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Detento;
use App\Models\PrisonUnit;
use App\Models\Visitante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Buscar cliente (Customer) simulando a busca de 'Filho'
     * GET /api/v1/pdv/filhos/search
     * opções usadas para venda balcão (dinheiro, débito, crédito, PIX).
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query('query');

        if (strlen($query) < 3) return response()->json([]);

        // Limpar para busca em telefone se for numérico
        $cleanQuery = preg_replace('/\D/', '', $query);

        $customers = Customer::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%");
            
        if (!empty($cleanQuery)) {
            $customers->orWhere('phone', 'like', "%{$cleanQuery}%");
        }

        $customers = $customers->orderBy('id', 'desc')->limit(10)->get();

        if ($customers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente não encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customers->map(fn ($customer) => $this->mapCustomerForPdv($customer)),
        ]);
    }

    /**
     * Busca cliente pelo "CPF" (Usado pelo Kiosk)
     */
    public function showByCpf($identifier)
    {
        // No Jumbonline buscaremos por e-mail ou telefone para adaptar
        $customer = Customer::query()
            ->where('email', $identifier)
            ->orWhere('phone', 'like', "%{$identifier}%")
            ->first();

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Cadastro não encontrado.'], 404);
        }

        $firstname = strtok(trim($customer->name), " ");

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $customer->id,
                'name' => $firstname,
            ]
        ]);
    }

    /**
     * Listar clientes para o PDV
     */
    public function index(Request $request): JsonResponse
    {
        $query = Customer::query()->select('id', 'name', 'email', 'phone');

        if ($request->filled('search')) {
            $search = $request->search;
            $cleanSearch = preg_replace('/\D/', '', $search);
            
            $query->where(function ($q) use ($search, $cleanSearch) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                if (!empty($cleanSearch)) {
                    $q->orWhere('phone', 'like', "%{$cleanSearch}%");
                }
            });
        }

        $customers = $query->orderBy('name')->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => $customers->map(fn ($customer) => $this->mapCustomerForPdv($customer)),
        ]);
    }

    /**
     * Cadastra um novo cliente diretamente pelo PDV, com os mesmos dados
     * mínimos exigidos para uma venda completa no site (Customer + Detento
     * + Visitante vinculados a uma Unidade Prisional).
     *
     * POST /api/v1/pdv/filhos
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers', 'email')],
            'phone' => ['nullable', 'string', Rule::phone()->country('BR')],
            'prison_unit_id' => ['required', 'integer', 'exists:prison_units,id'],

            'detento.name' => ['required', 'string', 'max:255'],
            'detento.matricula' => ['required', 'string', 'max:100'],
            'detento.raio' => ['required', 'string', 'max:50'],
            'detento.cela' => ['required', 'string', 'max:50'],

            'visitante.nome' => ['required', 'string', 'max:255'],
            'visitante.cep' => ['required', 'string', 'max:10'],
            'visitante.logradouro' => ['required', 'string', 'max:255'],
            'visitante.numero' => ['required', 'string', 'max:10'],
            'visitante.bairro' => ['required', 'string', 'max:255'],
            'visitante.cidade' => ['required', 'string', 'max:255'],
            'visitante.uf' => ['required', 'string', 'max:4'],
        ], [
            'name.required' => 'Informe o nome completo do cliente.',
            'email.required' => 'Informe um e-mail válido para o cliente.',
            'email.unique' => 'Já existe um cliente cadastrado com este e-mail.',
            'phone.phone' => 'Informe um telefone válido (com DDD).',
            'prison_unit_id.required' => 'Selecione a Unidade Prisional.',
            'prison_unit_id.exists' => 'Unidade Prisional inválida.',
            'detento.name.required' => 'Informe o nome completo do detento.',
            'detento.matricula.required' => 'O campo matrícula é obrigatório.',
            'detento.raio.required' => 'O campo raio é obrigatório.',
            'detento.cela.required' => 'O campo cela é obrigatório.',
            'visitante.nome.required' => 'Informe o nome do visitante/remetente.',
            'visitante.cep.required' => 'O CEP é obrigatório.',
            'visitante.logradouro.required' => 'O logradouro é obrigatório.',
            'visitante.numero.required' => 'O número é obrigatório.',
            'visitante.bairro.required' => 'O bairro é obrigatório.',
            'visitante.cidade.required' => 'A cidade é obrigatória.',
            'visitante.uf.required' => 'A UF é obrigatória.',
        ]);

        $customer = DB::transaction(function () use ($validated) {
            $customer = Customer::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'phone_country' => !empty($validated['phone']) ? 'BR' : null,
                'password' => Hash::make(Str::random(40)),
            ]);

            Detento::create([
                'name' => $validated['detento']['name'],
                'matricula' => $validated['detento']['matricula'],
                'raio' => $validated['detento']['raio'],
                'cela' => $validated['detento']['cela'],
                'customer_id' => $customer->id,
                'prison_unit_id' => $validated['prison_unit_id'],
            ]);

            Visitante::create([
                'nome' => $validated['visitante']['nome'],
                'cep' => $validated['visitante']['cep'],
                'logradouro' => $validated['visitante']['logradouro'],
                'numero' => $validated['visitante']['numero'],
                'bairro' => $validated['visitante']['bairro'],
                'cidade' => $validated['visitante']['cidade'],
                'uf' => $validated['visitante']['uf'],
                'customer_id' => $customer->id,
                'prison_unit_id' => $validated['prison_unit_id'],
            ]);

            return $customer;
        });

        return response()->json([
            'success' => true,
            'message' => 'Cliente cadastrado com sucesso.',
            'data' => $this->mapCustomerForPdv($customer),
        ], 201);
    }

    /**
     * Retorna os dados completos de um cliente cadastrado (Customer +
     * Detento + Visitante + Unidade Prisional), para a tela de
     * revisão/edição no PDV antes de seguir para o pagamento.
     *
     * GET /api/v1/pdv/filhos/{id}
     */
    public function show($id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente não encontrado.',
            ], 404);
        }

        $customer->load(['detentos', 'visitantes']);
        $detento = $customer->detentos->first();
        $visitante = $customer->visitantes->first();

        $prisonUnitId = optional($detento)->prison_unit_id ?? optional($visitante)->prison_unit_id;
        $prisonUnit = $prisonUnitId
            ? PrisonUnit::query()->select('id', 'name', 'cidade', 'uf', 'cep')->find($prisonUnitId)
            : null;

        return response()->json([
            'success' => true,
            'data' => array_merge($this->mapCustomerForPdv($customer), [
                'prison_unit_id' => $prisonUnitId,
                'prison_unit' => $prisonUnit,
                'detento' => $detento ? [
                    'name' => $detento->name,
                    'matricula' => $detento->matricula,
                    'raio' => $detento->raio,
                    'cela' => $detento->cela,
                ] : null,
                'visitante' => $visitante ? [
                    'nome' => $visitante->nome,
                    'cep' => $visitante->cep,
                    'logradouro' => $visitante->logradouro,
                    'numero' => $visitante->numero,
                    'bairro' => $visitante->bairro,
                    'cidade' => $visitante->cidade,
                    'uf' => $visitante->uf,
                ] : null,
            ]),
        ]);
    }

    /**
     * Atualiza os dados de um cliente já cadastrado (Customer + Detento +
     * Visitante)
     * PUT /api/v1/pdv/filhos/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente não encontrado.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'string', Rule::phone()->country('BR')],

            'prison_unit_id' => ['required', 'integer', 'exists:prison_units,id'],

            'detento.name' => ['required', 'string', 'max:255'],
            'detento.matricula' => ['required', 'string', 'max:100'],
            'detento.raio' => ['required', 'string', 'max:50'],
            'detento.cela' => ['required', 'string', 'max:50'],

            'visitante.nome' => ['required', 'string', 'max:255'],
            'visitante.cep' => ['required', 'string', 'max:10'],
            'visitante.logradouro' => ['required', 'string', 'max:255'],
            'visitante.numero' => ['required', 'string', 'max:10'],
            'visitante.bairro' => ['required', 'string', 'max:255'],
            'visitante.cidade' => ['required', 'string', 'max:255'],
            'visitante.uf' => ['required', 'string', 'max:4'],
        ], [
            'name.required' => 'Informe o nome completo do cliente.',
            'email.required' => 'Informe um e-mail válido para o cliente.',
            'email.unique' => 'Já existe um cliente cadastrado com este e-mail.',
            'phone.phone' => 'Informe um telefone válido (com DDD).',
            'prison_unit_id.required' => 'Selecione a Unidade Prisional.',
            'prison_unit_id.exists' => 'Unidade Prisional inválida.',
            'detento.name.required' => 'Informe o nome completo do detento.',
            'detento.matricula.required' => 'O campo matrícula é obrigatório.',
            'detento.raio.required' => 'O campo raio é obrigatório.',
            'detento.cela.required' => 'O campo cela é obrigatório.',
            'visitante.nome.required' => 'Informe o nome do visitante/remetente.',
            'visitante.cep.required' => 'O CEP é obrigatório.',
            'visitante.logradouro.required' => 'O logradouro é obrigatório.',
            'visitante.numero.required' => 'O número é obrigatório.',
            'visitante.bairro.required' => 'O bairro é obrigatório.',
            'visitante.cidade.required' => 'A cidade é obrigatória.',
            'visitante.uf.required' => 'A UF é obrigatória.',
        ]);

        DB::transaction(function () use ($customer, $validated) {
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'phone_country' => !empty($validated['phone']) ? 'BR' : null,
            ]);

            Detento::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'name' => $validated['detento']['name'],
                    'matricula' => $validated['detento']['matricula'],
                    'raio' => $validated['detento']['raio'],
                    'cela' => $validated['detento']['cela'],
                    'prison_unit_id' => $validated['prison_unit_id'],
                ]
            );

            Visitante::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'nome' => $validated['visitante']['nome'],
                    'cep' => $validated['visitante']['cep'],
                    'logradouro' => $validated['visitante']['logradouro'],
                    'numero' => $validated['visitante']['numero'],
                    'bairro' => $validated['visitante']['bairro'],
                    'cidade' => $validated['visitante']['cidade'],
                    'uf' => $validated['visitante']['uf'],
                    'prison_unit_id' => $validated['prison_unit_id'],
                ]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Dados do cliente atualizados com sucesso.',
            'data' => $this->mapCustomerForPdv($customer->fresh()),
        ]);
    }

    /**
     * Mapeia um Customer para o formato consumido pelo PDV 
     */
    private function mapCustomerForPdv(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'full_name' => $customer->name,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'cpf_formatted' => $customer->phone ?? $customer->email, // PDV usa para exibir na lista
            'photo_url' => $customer->getFirstMediaUrl('avatar') ?: url('/img/avatar.svg'),
        ];
    }
}