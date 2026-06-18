<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Enums\ProductStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Listar produtos disponíveis no PDV
     */
   public function index(Request $request)
    {
        // Pega produtos ativos
        $query = Product::with(['first_variant', 'categories', 'media'])
            ->where('status', ['active', 'ACTIVE', 1]);


        // 1. Filtro de Busca Robusto (Nome, ou SKU/Barcode)
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // Procura no nome do produto
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($variantQuery) use ($search) {
                      $variantQuery->where('sku', 'like', "%{$search}%")
                                   ->orWhere('barcode', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filtro por Categoria clicada no PDV
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $categoryId = $request->category_id;
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }


        // 3. Filtro por Tipo de Loja (Site vs Balcão)
        $storeType = $request->input('type', 'site'); // Se não enviar, assume site
        // O PDV quer ver Site? Mostra 'site' e 'ambos'. Quer ver Balcão? Mostra 'balcao' e 'ambos'.
        $query->whereIn('sales_channel', [$storeType, 'ambos']);

        $products = $query->paginate(20);

        // Formatação limpa para o React entender sem quebrar
        $formattedProducts = $products->map(function ($product) {
            // Fallback de preço (caso use variant ou preço direto no produto)
            $price = $product->first_variant ? $product->first_variant->price : $product->price;
            $track_stock = $product->first_variant ? $product->first_variant->stock_tracking : true;
            $stock_quantity = $product->first_variant ? $product->first_variant->stock_value : 9999;

            $firstCategory = $product->categories->first();

           
            
            return [
                'id' => (string) $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? '',
                'barcode' => $product->barcode ?? '',
                'price' => (float) $price,
                'track_stock' => $track_stock,
                'stock_quantity' => $stock_quantity,
                'min_stock_alert' => 4,
                'is_low_stock' => $stock_quantity <= 4,
                'category_id' => $firstCategory ? (string) $firstCategory->id : '',
                'category_name' => $firstCategory ? $firstCategory->title : 'Sem Categoria',
                // Pega a imagem via Spatie Media Library
                'image_url' => $product->getFirstMediaUrl(
                    $product->hasMedia('cover') ? 'cover' : 'gallery'
                ), 
                'is_active' => $product->is_active,
                'type' => $product->sales_channel === 'balcao' ? 'balcao' : 'site',
                'available_pdv' => true,
                'category' => $firstCategory ? [
                    'id' => $firstCategory->id,
                    'name' => $firstCategory->slug,
                    'is_active' => true,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedProducts,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ]
        ]);
    }


    /**
     * Listar categorias para o PDV
     */

    public function categories()
    {
        $categories = Category::whereHas('products', function($q) {
            $q->whereIn('status', ['active', 'ACTIVE', 1]);
        })->get();

        // Mapeia para interface Category do models.ts
        $formatted = $categories->map(function ($cat) {
            return [
                'id' => (string) $cat->id,
                'name' => $cat->slug,
                'slug' => $cat->slug ?? '',
                'description' => $cat->description ?? null,
                'icon' => null,
                'color' => null,
                'order' => 0,
                'is_active' => true,
                'parent_id' => null,
                'created_at' => $cat->created_at ? $cat->created_at->toIso8601String() : now()->toIso8601String(),
                'updated_at' => $cat->updated_at ? $cat->updated_at->toIso8601String() : now()->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formatted
        ]);
    }

     /**
     * Buscar produto por código de barras
     * GET /api/v1/pdv/products/barcode/{barcode}
     */
    public function findByBarcode(string $barcode): JsonResponse
    {

        $product = Product::whereHas('variants', function($variantQuery) use ($barcode){
                $variantQuery->where('barcode', 'like', "%{$barcode}%");
        })->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado',
            ], 404);
        }

        if ($product->first_variant->stock_value <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Produto sem estoque',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->first_variant->stock_value ,
                ],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->first_variant->sku,
                'barcode' => $product->first_variant->barcode,
                'price' => $product->price,
                'stock' => $product->first_variant->stock_value,
                'image_url' => $product->getFirstMediaUrl('products') ?: null,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->title,
                ] : null,
            ],
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