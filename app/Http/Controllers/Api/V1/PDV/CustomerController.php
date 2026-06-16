<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Buscar cliente (Customer) simulando a busca de 'Filho'
     * GET /api/v1/pdv/filhos/search
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

        // Mapeando para o formato que o PDV espera
        $dataMapped = $customers->map(function($customer) {
            return [
                'id'                 => $customer->id,
                'full_name'          => $customer->name,
                'cpf_formatted'      => $customer->phone ?? $customer->email, // PDV usa para exibir
                'credit_limit'       => 9999.99, // Valor simulado para permitir compra livre no PDV
                'credit_used'        => 0.00,
                'credit_available'   => 9999.99, 
                'is_blocked_by_debt' => false, // Cliente do ecommerce não tem bloqueio de crédito nativo
                'block_reason'       => null,
                'photo_url'          => $customer->getFirstMediaUrl('avatar') ?: url('/img/avatar.svg')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $dataMapped
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
                'credit_available' => 9999.99,
                'is_blocked_by_debt' => false,
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

        $customers = $query->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'cpf' => $customer->phone ?? $customer->email, // Adaptação de campo
                    'photo_url' => $customer->getFirstMediaUrl('avatar') ?: url('/img/avatar.svg'),
                    'credit_available' => 9999.99,
                    'is_blocked' => false,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }
}