<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PrisonUnit;
use App\Models\PrisonCategory;
use App\Models\PaymentMethod; // <--- Importante!
use App\Models\Payment;
use App\Models\CashSession;
use App\Models\CashMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Importando os Enums
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;

class PDVOrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:customers,id',
            'filho_id' => 'nullable|exists:customers,id',
            'payment_method_chosen' => 'required'
        ]);


        return DB::transaction(function () use ($request) {
            $total = 0;
            $itemsData = [];

            $employee = $request->user();

            // 1. Cálculo seguro dos itens
            
            foreach ($request->items as $item) {
                // Trazemos o produto já com sua primeira variante carregada
                $product = Product::with('first_variant')->find($item['product_id']);
                
                if (!$product) continue;

                $quantity = $item['quantity'];
                // Pega o preço (Se a variante tiver preço específico, usa ela, senão usa do produto)
                $price = $product->first_variant ? $product->first_variant->price : $product->price;
                $subtotal = $price * $quantity;
                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'variant_id' => $product->first_variant ? $product->first_variant->id : null,
                    'quantity' => $quantity,
                    'price' => $price,
            }

            // 2. Unidade Prisional de Balcão
            $pdvCategory = PrisonCategory::firstOrCreate(['name' => 'Vendas Balcão / PDV']);
            $pdvUnit = PrisonUnit::firstOrCreate(
                ['name' => 'Venda Presencial PDV'],
                [
                    'prison_category_id' => $pdvCategory->id,
                    'logradouro' => 'Loja Física (Balcão)',
                    'numero' => 'S/N',
                    'bairro' => 'Centro',
                    'cidade' => 'Local',
                    'uf' => 'SP',
                    'cep' => '00000000',
                    'phone' => '+5511999999999'
                ]
            );

            // 3. BUSCA O MÉTODO DE PAGAMENTO (Fallback para dinheiro)
            // Tenta pegar o que veio do app do PDV, se não vier nada, usa 'dinheiro'
            $requestedMethod = $request->input('payment_method_chosen', 'dinheiro');
            
            $paymentMethod = PaymentMethod::where('identifier', 'like', "%{$requestedMethod}%")
                ->orWhere('name', 'like', "%{$requestedMethod}%")
                ->first();

            // 4. Cria a Order resolvendo o erro do payment_method_id
            $order = Order::create([
                'customer_id' => $request->input('filho_id') ?? $request->input('customer_id'),
                'prison_unit_id' => $pdvUnit->id,
                'payment_method_id' => $paymentMethod ? $paymentMethod->id : 1, // <--- RESOLVE O ERRO 1364
                'order_status' => OrderStatus::PENDING, 
                'payment_status' => PaymentStatus::PENDING,
                'shipping_price' => 0.00,
                'shipping_status' => ShippingStatus::UNSHIPPED ?? null, 
                'notes' => 'Pedido de balcão (PDV Desktop)',
                'meta' => [
                    'origin' => 'pdv_desktop',
                    'operator_id' => $employee->id
                ],
                'tax_breakdown' => [],
            ]);

            // 5. Salva Itens e Baixa Estoque
            foreach ($itemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);

                // Baixa de estoque na tabela products
                $productToUpdate = Product::with('first_variant')
                    ->lockForUpdate()
                    ->find($itemData['product_id']);

                if ($productToUpdate) {
                    // Verifica se o produto usa controle de estoque pela variante
                    if ($productToUpdate->first_variant) {
                        if ($productToUpdate->first_variant->stock_value < $itemData['quantity']) {
                            throw new \Exception("Estoque insuficiente para a variação do produto: {$productToUpdate->name}");
                        }
                        $productToUpdate->first_variant->decrement('stock_value', $itemData['quantity']);
                    } 
                    // Caso contrário, desconta do produto principal
                    else {
                        if ($productToUpdate->stock < $itemData['quantity']) {
                            throw new \Exception("Estoque insuficiente para o produto: {$productToUpdate->name}");
                        }
                        $productToUpdate->decrement('stock', $itemData['quantity']);
                    }
                }
            }


            if (strtolower($requestedMethod) !== 'pix') {

                Log::info('Entrei, Meio de Pagamento é: '.$requestedMethod );
                
                $cashSession = CashSession::where('employee_id', $employee->id)->where('status', 'open')->first();

                // 2. CRIA O PAGAMENTO
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'cash_session_id' => $cashSession ? $cashSession->id : null,
                    'amount' => $order->total,
                    'currency' => 'BRL',
                    'status' => PaymentStatus::PAID,
                    'reference' => 'PDV-' . uniqid(),
                ]);


                $order->update([
                    'order_status' => OrderStatus::COMPLETED,
                    'payment_status' => PaymentStatus::PAID
                ]);

                //Atuaizando movimento de caixa
                if ($cashSession) {

                    $method = strtolower($requestedMethod);
                    
                    CashMovement::create([
                        'cash_session_id' => $cashSession->id,
                        'order_id' => $order->id,
                        'user_id' => auth()->id(),
                        'type' => 'sale',
                        'amount' => $order->total, 
                        'payment_method' => $method, 
                        'description' => "Venda #{$order->id}"." Balcão"
                    ]);
                }

            }


            return response()->json([
                'success' => true,
                'message' => 'Pedido criado com sucesso.',
                'data' => [
                    'id' => $order->id,
                    'total' => $total,
                    'status' => 'pending'
                ]
            ], 201);
        });
    }
}