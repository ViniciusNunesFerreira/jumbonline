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

class PDVPaymentController extends Controller
{
    public function process(Request $request, PDVMercadoPagoService $mpService)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string' // ex: 'dinheiro', 'pix', 'cartao_credito'
        ]);

        $order = Order::findOrFail($request->order_id);
        $employee = $request->user();
        
        // Pega o caixa aberto do operador
        $session = CashSession::where('employee_id', $employee->id)->where('status', 'open')->first();

        $requestedMethod = $request->input('payment_method');

        $paymentMethod = PaymentMethod::where('identifier', 'like', "%{$requestedMethod}%")
            ->orWhere('name', 'like', "%{$requestedMethod}%")
            ->first();


        $payment = Payment::create([
            'order_id' => $order->id,
            'cash_session_id' => $session ? $session->id : null,
            'amount' => $request->amount,
            'currency' => 'BRL',
            'status' => PaymentStatus::PENDING,
            'reference' => 'PDV-' . uniqid(),
        ]);

        if ($paymentMethod) {
            $order->update(['payment_method_id' => $paymentMethod->id]);
        }

        \Log::info($requestedMethod);


        // === SE FOR PIX ===
        if (strtolower($requestedMethod) === 'pix') {
            \Log::info('entrei no pix');
            try {
                $pixData = $mpService->generatePix($payment, $order, $order->customer);
                
                $payment->update(['reference' => $pixData['mp_payment_id']]);

                return response()->json([
                    'success' => true,
                    'is_pix' => true,
                    'data' => [
                        'id' => $payment->id,
                        'qr_code' => $pixData['qr_code'],
                        'qr_code_base64' => $pixData['qr_code_base64'],
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }

        // === SE FOR DINHEIRO OU MÁQUINA DE CARTÃO FÍSICA (Baixa automática) ===
        $payment->update(['status' => PaymentStatus::PAID]);
        $order->update([
            'order_status' => OrderStatus::COMPLETED,
            'payment_status' => PaymentStatus::PAID
        ]);

        return response()->json([
            'success' => true,
            'is_pix' => false,
            'message' => 'Pagamento processado com sucesso.',
            'data' => [
                'payment_id' => $payment->id,
                'status' => 'paid'
            ]
        ]);
    }
    
    // Rota que o PDV fica fazendo "polling" para saber se o PIX foi pago
    public function checkStatus($paymentId)
    {
        $payment = Payment::find($paymentId);
        
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Não encontrado'], 404);
        }

        // Suporte seguro a Enums do PHP 8+ ou Strings convencionais
        $statusStr = is_object($payment->status) ? $payment->status->value : $payment->status;
        $isPaid = in_array(strtolower($statusStr), ['paid', 'approved', 'completed']);

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $isPaid ? 'approved' : 'pending' 
            ]
        ]);
    }

    /**
     * Cancela a intenção de pagamento se o operador desistir na tela do QR Code
     */
    public function cancel($paymentId)
    {
        $payment = Payment::find($paymentId);
        
        if ($payment && $payment->status === PaymentStatus::PENDING) {
            // Marca como falho/cancelado para não sujar o caixa
            $payment->delete(); // Ou você pode atualizar o status para cancelado
            
            // Cancela a ordem vinculada para não prender o estoque
            if ($payment->order && $payment->order->order_status === OrderStatus::PENDING) {
                // Devolve o estoque
                foreach ($payment->order->orderItems as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
                
                // Exclui ou cancela a order
                $payment->order->delete();
            }
        }

        return response()->json([
            'success' => true, 
            'message' => 'Pagamento cancelado com sucesso.'
        ]);
    }



}