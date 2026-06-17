<?php

namespace App\Http\Controllers\Api\V1\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PDVMercadoPagoService;
use App\Models\Payment;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;

class PDVMercadoPagoWebhookController extends Controller
{
    public function handle(Request $request, PDVMercadoPagoService $mpService)
    {
        // MP envia { "action": "payment.updated", "data": { "id": "123456" } }
        if ($request->input('action') === 'payment.updated' || $request->input('type') === 'payment') {
            
            $mpPaymentId = $request->input('data.id');
            
            try {
                $mpData = $mpService->getPayment($mpPaymentId);
                
                // O MP devolve a referência externa que mandamos (nosso Payment ID)
                $paymentId = $mpData['external_reference'];
                $status = $mpData['status']; // 'approved', 'pending', 'rejected'

                if ($status === 'approved') {
                    $payment = Payment::find($paymentId);
                    
                    if ($payment && $payment->status !== PaymentStatus::PAID) {
                        // Dá baixa no pagamento
                        $payment->update(['status' => PaymentStatus::PAID]);
                        
                        // Dá baixa no pedido
                        if ($payment->order) {
                            $payment->order->update([
                                'order_status' => OrderStatus::COMPLETED,
                                'payment_status' => PaymentStatus::PAID
                            ]);
                        }
                        
                        Log::info("PDV PIX Aprovado: Pedido {$payment->order_id}");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Erro no Webhook PDV PIX: " . $e->getMessage());
                return response()->json(['error' => 'Failed to process'], 500);
            }
        }

        return response()->json(['success' => true]);
    }
}