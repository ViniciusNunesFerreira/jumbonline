<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\PrisonUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrisonUnitController extends Controller
{
    /**
     * Lista Unidades Prisionais para seleção no cadastro de cliente do PDV.
     * GET /api/v1/pdv/prison-units?search=...
     */
    public function index(Request $request): JsonResponse
    {
        $query = PrisonUnit::query()
            ->select('id', 'name', 'cidade', 'uf', 'bairro')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->query('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('cidade', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->limit(100)->get(),
        ]);
    }
}
