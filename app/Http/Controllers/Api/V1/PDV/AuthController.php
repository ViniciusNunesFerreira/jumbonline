<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Login no PDV
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:255'],
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        // Verificar se usuário está banido/inativo (Usando a trait Bannable do Jumbonline)
        if ($employee->isBanned()) {
            throw ValidationException::withMessages([
                'email' => ['Sua conta está inativa ou bloqueada. Contate o administrador.'],
            ]);
        }

        // Revogar tokens antigos do mesmo dispositivo
        $employee->tokens()->where('name', $request->device_name)->delete();

        // Abilities básicas no Sanctum
        $abilities = $employee->is_admin ? ['*'] : ['pdv:access'];

        // Criar novo token com expiração de 24 horas
        $token = $employee->createToken(
            $request->device_name,
            $abilities,
            now()->addHours(24)
        );

        Log::info("Login PDV realizado por: " . $employee->email);

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'user' => $this->formatUserPayload($employee),
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->accessToken->expires_at?->toIso8601String(),
                'permissions' => $this->getUserPermissions($employee),
            ],
        ], 200);
    }

    /**
     * Obter dados do usuário autenticado
     */
    public function me(Request $request): JsonResponse
    {
        /** @var Employee $employee */
        $employee = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $this->formatUserPayload($employee),
                'permissions' => $this->getUserPermissions($employee),
                'device_info' => [
                    'current_device' => $employee->currentAccessToken()->name,
                    'token_expires_at' => $employee->currentAccessToken()->expires_at?->toIso8601String(),
                ],
            ],
        ], 200);
    }

    /**
     * Logout do PDV
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        Log::info("Logout PDV: " . $request->user()->email);

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso',
        ], 200);
    }

    /**
     * Refresh do token
     */
    public function refresh(Request $request): JsonResponse
    {
        $employee = $request->user();
        $currentToken = $employee->currentAccessToken();
        $deviceName = $currentToken->name;

        $currentToken->delete();

        $abilities = $employee->is_admin ? ['*'] : ['pdv:access'];

        $newToken = $employee->createToken(
            $deviceName,
            $abilities,
            now()->addHours(24)
        );

        return response()->json([
            'success' => true,
            'message' => 'Token renovado com sucesso',
            'data' => [
                'token' => $newToken->plainTextToken,
                'expires_at' => $newToken->accessToken->expires_at?->toIso8601String(),
            ],
        ], 200);
    }

    /**
     * Validar token atual
     */
    public function validateToken(Request $request): JsonResponse
    {
        $employee = $request->user();
        $token = $employee->currentAccessToken();

        return response()->json([
            'success' => true,
            'data' => [
                'valid' => true,
                'expires_at' => $token->expires_at?->toIso8601String(),
                'device_name' => $token->name,
                'user' => $this->formatUserPayload($employee),
            ],
        ], 200);
    }

    /**
     * Mapeia os dados do usuário para o formato esperado pelo front
     */
    private function formatUserPayload(Employee $employee): array
    {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'email' => $employee->email,
            'roles' => $employee->is_admin ? ['admin'] : ['operator'],
            'avatar_url' => $employee->getFirstMediaUrl('avatar') ?: url('/img/avatar.svg'),
        ];
    }

    /**
     * Simula as permissões detalhadas para o front-end
     */
    private function getUserPermissions(Employee $employee): array
    {
        $isAdmin = $employee->is_admin;

        return [
            'can_create_orders' => true,
            'can_cancel_orders' => $isAdmin,
            'can_give_discounts' => $isAdmin,
            'can_override_limits' => $isAdmin,
            'can_view_products' => true,
            'can_manage_products' => $isAdmin,
            'can_view_filhos' => true, // Aqui filhos atua como customers no seu PDV
            'can_manage_filhos' => $isAdmin,
            'max_discount_percent' => $isAdmin ? 100 : 10,
            'roles' => $employee->is_admin ? ['admin'] : ['operator'],
            'all_permissions' => $isAdmin ? ['*'] : ['pdv.basic_access'],
        ];
    }
}