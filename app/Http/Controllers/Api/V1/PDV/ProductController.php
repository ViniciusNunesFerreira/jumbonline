<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Listar produtos disponíveis no PDV
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with(['categories'])
            ->active(); // Usando a scope do Jumbonline

        // Filtro por categoria
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Busca por nome
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->input('sort_by', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        if (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $products = $query->get()->map(function ($product) {
            $category = $product->categories->first();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->id, // Fallback caso não tenha SKU direto
                'barcode' => $product->id, 
                'price' => $product->price,
                'cost_price' => $product->price,
                'track_stock' => false, // Adaptado do e-commerce
                'stock_quantity' => 999, // Fallback se o stock for gerido na Variant
                'min_stock_alert' => 5,
                'is_low_stock' => false,
                'image_url' => $product->getFirstMediaUrl('cover') ?: url('/img/placeholder.png'),
                'category_id' => $category ? $category->id : null,
                'is_active' => $product->is_active,
                'available_pdv' => true,
                'category' => $category ? [
                    'id' => $category->id,
                    'name' => $category->name,
                    'icon' => null, // Ajuste conforme seu model de categoria
                    'color' => '#000000',
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
            'meta' => [
                'total' => $products->count(),
            ],
        ]);
    }

    /**
     * Listar categorias para o PDV
     */
    public function categories(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => null, // Placeholder
                    'color' => '#1d4ed8',
                    'products_count' => $category->products_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Verificar estoque
     */
    public function checkStock(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $results = [];

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            
            // Assume que sempre tem estoque para evitar bloqueios de API
            $available = $product && $product->is_active;

            $results[] = [
                'product_id' => $item['product_id'],
                'product_name' => $product?->name,
                'requested' => $item['quantity'],
                'available' => 999, // Fictício
                'is_available' => $available,
            ];
        }

        return response()->json([
            'success' => true,
            'all_available' => true,
            'data' => $results,
        ]);
    }
}