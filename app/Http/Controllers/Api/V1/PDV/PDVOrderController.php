<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PrisonUnit;
use App\Models\PrisonCategory;
use App\Models\Detento;
use App\Models\Visitante;
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
            'payment_method_chosen' => 'required',
            // Frete (PAC/SEDEX), somente quando a venda tem Unidade
            // Prisional real como destino — ver resolução abaixo.
            'shipping_carrier' => 'nullable|string|in:pac,sedex',
            'shipping_price' => 'nullable|numeric|min:0',
        ]);


        return DB::transaction(function () use ($request) {
            $total = 0;
            $itemsData = [];

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
                ];
            }

            // 2. Unidade Prisional do pedido
            $customerId = $request->input('filho_id') ?? $request->input('customer_id');
            $detento = null;
            $visitante = null;
            $detentoId = null;
            $visitanteId = null;
            $resolvedPrisonUnitId = null;

            if ($customerId) {
                $detento = Detento::where('customer_id', $customerId)->first();
                $visitante = Visitante::where('customer_id', $customerId)->first();

                $detentoId = optional($detento)->id;
                $visitanteId = optional($visitante)->id;

                $resolvedPrisonUnitId = optional($detento)->prison_unit_id
                    ?? optional($visitante)->prison_unit_id;
            }

            $shippingCarrier = null;
            $shippingPrice = 0.00;

            if ($resolvedPrisonUnitId) {
                // Venda com destino real a uma Unidade Prisional: usa o
                // frete calculado/escolhido pelo operador na tela de
                // cadastro do cliente (ShippingController@quote).
                $requestedCarrier = $request->input('shipping_carrier');
                if ($requestedCarrier) {
                    $shippingCarrier = $requestedCarrier;
                    $shippingPrice = (float) $request->input('shipping_price', 0);
                }
            } else {
                // Fallback: sem cliente cadastrado com unidade prisional
                // vinculada — mantém o comportamento histórico de venda de
                // balcão (retirada no local, sem frete), criando a
                // Unidade/Categoria "mock" somente quando realmente
                // necessário (lazy).
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

                $resolvedPrisonUnitId = $pdvUnit->id;
            }

            // 3. BUSCA O MÉTODO DE PAGAMENTO (Fallback para dinheiro)
            // Tenta pegar o que veio do app do PDV, se não vier nada, usa 'dinheiro'
            $requestedMethod = $request->input('payment_method_chosen', 'dinheiro');
            
            $paymentMethod = PaymentMethod::where('identifier', 'like', "%{$requestedMethod}%")
                ->orWhere('name', 'like', "%{$requestedMethod}%")
                ->first();

            // 4. Cria a Order resolvendo o erro do payment_method_id
            $order = Order::create([
                'customer_id' => $customerId,
                'detento_id' => $detentoId,
                'visitante_id' => $visitanteId,
                'prison_unit_id' => $resolvedPrisonUnitId,
                'payment_method_id' => $paymentMethod ? $paymentMethod->id : 1, // <--- RESOLVE O ERRO 1364
                'order_status' => OrderStatus::PENDING, 
                'payment_status' => PaymentStatus::PENDING,
                'shipping_rate' => $shippingCarrier,
                'shipping_price' => $shippingPrice,
                'shipping_status' => ShippingStatus::UNSHIPPED ?? null, 
                'notes' => $shippingCarrier
                    ? "Pedido PDV com envio para Unidade Prisional (frete: {$shippingCarrier})"
                    : 'Pedido de balcão (PDV Desktop)',
                'meta' => [
                    'origin' => 'pdv_desktop',
                    'operator_id' => $request->user()->id
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

                $cashSession = CashSession::where('employee_id', $request->user()->id )->where('status', 'open')->first();

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
                        'employee_id' => auth()->id(),
                        'type' => $method === 'dinheiro' ? 'in' : 'sale',
                        'amount' => $order->total, 
                        'description' => "Venda #{$order->id}"." Balcão"
                    ]);
                }

            }


            return response()->json([
                'success' => true,
                'message' => 'Pedido criado com sucesso.',
                'data' => [
                    'id' => $order->id,
                    'subtotal' => $total,
                    'shipping_price' => (float) $order->shipping_price,
                    'shipping_carrier' => $shippingCarrier,
                    // Total real cobrado do cliente, já incluindo o frete
                    'total' => $order->total,
                    'status' => 'pending'
                ]
            ], 201);
        });
    }
}