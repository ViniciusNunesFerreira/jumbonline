<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    /**
     * Proxy de consulta de CEP (ViaCEP) para o formulário de cadastro de
     * cliente do PDV, seguindo o mesmo padrão usado em
     * Guest\PurchaseComponents\CadastroDetento::changeDataVisitanteCep().
     *
     * Mantido no backend (em vez de chamada direta pelo app Tauri) para não
     * depender de liberação de domínio externo na configuração de rede do
     * client desktop.
     *
     * GET /api/v1/pdv/cep/{cep}
     */
    public function lookup(string $cep): JsonResponse
    {
        $cleanCep = preg_replace('/\D/', '', $cep);

        if (strlen($cleanCep) !== 8) {
            return response()->json([
                'success' => false,
                'message' => 'CEP inválido.',
            ], 422);
        }

        $result = Http::timeout(5)->get("https://viacep.com.br/ws/{$cleanCep}/json/");

        if (! $result->ok() || ($result->json('erro') === true)) {
            return response()->json([
                'success' => false,
                'message' => 'CEP não encontrado.',
            ], 404);
        }

        $data = $result->json();

        return response()->json([
            'success' => true,
            'data' => [
                'cep' => $data['cep'] ?? $cleanCep,
                'logradouro' => $data['logradouro'] ?? '',
                'bairro' => $data['bairro'] ?? '',
                'cidade' => $data['localidade'] ?? '',
                'uf' => $data['uf'] ?? '',
            ],
        ]);
    }
}
