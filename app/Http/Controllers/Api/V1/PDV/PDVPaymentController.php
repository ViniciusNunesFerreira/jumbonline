<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\CashSession;
use App\Services\PDVMercadoPagoService;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PDVPaymentController extends Controller
{
    public function process(Request $request, PDVMercadoPagoService $mpService)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string' 
        ]);

        $order = Order::findOrFail($request->order_id);
        $employee = $request->user();
        
        $session = CashSession::where('employee_id', $employee->id)->where('status', 'open')->first();
        $requestedMethod = $request->input('payment_method');

        $paymentMethod = PaymentMethod::where('identifier', 'like', "%{$requestedMethod}%")
            ->orWhere('name', 'like', "%{$requestedMethod}%")
            ->first();

        // 1. ATUALIZA A ORDER COM O MÉTODO DE PAGAMENTO (Local correto na arquitetura Jumbonline)
        if ($paymentMethod) {
            $order->update(['payment_method_id' => $paymentMethod->id]);
        }

        // 2. CRIA O PAGAMENTO (Sem o payment_method_id, pois essa coluna não existe aqui)
        $payment = Payment::create([
            'order_id' => $order->id,
            'cash_session_id' => $session ? $session->id : null,
            'amount' => $request->amount,
            'currency' => 'BRL',
            'status' => PaymentStatus::PENDING,
            'reference' => 'PDV-' . uniqid(),
        ]);

        // === SE FOR PIX ===
        if (strtolower($requestedMethod) === 'pix') {
            try {
                $pixData = $mpService->generatePix($payment, $order, $order->customer);
                $payment->update(['reference' => $pixData['mp_payment_id']]);

                return response()->json([
                    'success' => true,
                    'message' => 'PIX gerado com sucesso.',
                    'data' => $this->formatIntent($payment, $pixData)
                ], 200);

            } catch (\Exception $e) {
                Log::error('Erro PDV PIX: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

        // === SE FOR DINHEIRO OU CARTÃO ===
        $payment->update(['status' => PaymentStatus::PAID]);
        $order->update([
            'order_status' => OrderStatus::COMPLETED,
            'payment_status' => PaymentStatus::PAID
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pagamento processado com sucesso.',
            'data' => $this->formatIntent($payment)
        ], 200);
    }
    
    // Polling do PDV
    public function checkStatus($paymentId)
    {
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Não encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatIntent($payment)
        ]);
    }

    // Cancelamento
    public function cancel($paymentId)
    {
        $payment = Payment::find($paymentId);
        
        if ($payment) {
            $payment->delete(); 
            
            if ($payment->order) {

                foreach ($payment->order->orderItems as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    
                    if ($product) {
                        // $product->increment('stock_quantity', $item->quantity);
                        if ($item->variant_id) {
                            $variant = \App\Models\Variant::find($item->variant_id);
                            if ($variant) {
                                 $variant->increment('stock_value', $item->quantity);
                            }
                        }
                    }
                }
                $payment->order->delete();
            }
        }
        return response()->json(['success' => true, 'message' => 'Cancelado']);
    }

   /**
     * Formatador para a interface do PDV React
     */
    private function formatIntent($payment, $pixData = null)
    {
        // LÊ DIRETO DO BANCO DE DADOS: Ignora a classe Enum e evita o erro "Undefined property"
        $statusStr = (string) $payment->getRawOriginal('status');
        $isPaid = in_array(strtolower($statusStr), ['paid', 'approved', 'completed']);

        // DEDUZIR O MÉTODO
        $method = 'cash';
        $payment->loadMissing('order.paymentMethod'); 
        
        if ($payment->order && $payment->order->paymentMethod) {
            $name = strtolower($payment->order->paymentMethod->name);
            if (str_contains($name, 'pix')) $method = 'pix';
            elseif (str_contains($name, 'crédito') || str_contains($name, 'credito')) $method = 'credit_card';
            elseif (str_contains($name, 'débito') || str_contains($name, 'debito')) $method = 'debit_card';
        }

        $pdvStatus = $isPaid ? 'approved' : 'pending';

        $data = [
            'id' => (string) $payment->id,
            'order_id' => (string) $payment->order_id,
            'payment_method' => $method,
            'amount' => (float) $payment->amount,
            'status' => $pdvStatus,
            'is_pix' => $method === 'pix',
            'is_approved' => $isPaid,
            'is_pending' => !$isPaid,
            'is_cancelled' => false,
        ];

        if ($method === 'pix' && $pixData) {
            $data['pix'] = [
                'qr_code' => $pixData['qr_code'] ?? '',
                'qr_code_base64' => $pixData['qr_code_base64'] ?? '',
                'expires_in_seconds' => 600,
                'expiration' => now()->addMinutes(10)->toIso8601String(),
            ];
        }

        return $data;
    }
}