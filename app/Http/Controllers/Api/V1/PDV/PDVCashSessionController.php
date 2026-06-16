<?php

namespace App\Http\Controllers\Api\V1\PDV;

use App\Http\Controllers\Controller;
use App\Models\CashSession;
use App\Models\CashMovement;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PDVCashSessionController extends Controller
{
    /**
     * Verifica status atual (Retorna as chaves exatas que o App do PDV precisa)
     */
    public function status(Request $request)
    {
        $employee = $request->user();

        $session = CashSession::where('employee_id', $employee->id)
            ->where('status', 'open')
            ->with(['movements' => function($q) {
                $q->latest()->limit(5); // Últimas movimentações
            }])
            ->first();


        if (!$session) {
            return response()->json(['status' => 'closed']);
        }

        // Converte o model para array e simula o "user_id" para o front não quebrar
        $sessionData = $session->toArray();
        $sessionData['user_id'] = $session->employee_id;

        $summary = [
            'dinheiro' => $session->movements()->where('type', 'in')->sum('amount') 
                        - $session->movements()->where('type', 'out')->sum('amount'),
            
            'cartao_credito' => Payment::where('cash_session_id', $session->id)
                ->whereHas('order.paymentMethod', fn($q) => 
                    $q->where('name', 'like', '%Crédito%')
                      ->orWhere('display_name', 'like', '%Crédito%')
                )->sum('amount'),
                
            'cartao_debito' => Payment::where('cash_session_id', $session->id)
                ->whereHas('order.paymentMethod', fn($q) => 
                    $q->where('name', 'like', '%Débito%')
                      ->orWhere('display_name', 'like', '%Débito%')
                )->sum('amount'),
                
            'pix' => Payment::where('cash_session_id', $session->id)
                ->whereHas('order.paymentMethod', fn($q) => 
                    $q->where('name', 'like', '%PIX%')
                      ->orWhere('display_name', 'like', '%PIX%')
                )->sum('amount'),
        ];

        return response()->json([
            'status' => 'open',
            'session' => $session,
            'summary' => $summary
        ]);
    }

    /**
     * 1. Abertura de Caixa (Com Auto-Correção de Formato)
     */
    public function open(Request $request)
    {

        $employee = $request->user();


        // 1. Evita erro 422 por duplicidade
        if (CashSession::where('employee_id', $employee->id)->where('status', 'open')->exists()) {
            throw ValidationException::withMessages([
                'message' => 'Você já possui um caixa aberto no momento.'
            ]);
        }

       

        // 2. Evita erro 422 por formatação de String (Troca vírgula por ponto)
        $balance = str_replace(['R$', ' ', ','], ['', '', '.'], $request->input('opening_balance', '0'));
        $request->merge(['opening_balance' => $balance]);

        $request->validate([
            'opening_balance' => 'required|numeric|min:0'
        ]);

        return DB::transaction(function () use ($request, $employee) {
            $session = CashSession::create([
                'employee_id' => $employee->id,
                'device_name' => $request->input('device_id', 'Terminal PDV'), // PDV envia device_id
                'opened_at' => now(),
                'opening_balance' => $request->opening_balance,
                'status' => 'open'
            ]);

            // Registra movimento de abertura
            CashMovement::create([
                'cash_session_id' => $session->id,
                'employee_id' => $employee->id,
                'type' => 'in',
                'amount' => $request->opening_balance,
                'description' => 'Abertura de Caixa - Fundo de Troco'
            ]);

            // Devolve 'user_id' para compatibilidade do App
            $sessionData = $session->toArray();
            $sessionData['user_id'] = $session->employee_id;

            return response()->json([
                'message' => 'Caixa aberto com sucesso', 
                'session' => $sessionData
            ]);
        });
    }

    /**
     * 3. Sangria e Suprimento
     */
    public function movement(Request $request)
    {
        // Auto-Correção de formatação
        $amount = str_replace(['R$', ' ', ','], ['', '', '.'], $request->input('amount', '0'));
        $request->merge(['amount' => $amount]);

        $request->validate([
            'type' => 'required|in:bleed,supply',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255'
        ]);

        $employee = $request->user();
        $session = CashSession::where('employee_id', $employee->id)->where('status', 'open')->firstOrFail();

        // Traduz as variáveis do Front (bleed/supply) para o DB (in/out)
        $dbType = $request->type === 'bleed' ? 'out' : 'in';

        $movement = CashMovement::create([
            'cash_session_id' => $session->id,
            'employee_id' => $employee->id,
            'type' => $dbType,
            'amount' => abs($request->amount),
            'description' => $request->description
        ]);

        return response()->json(['message' => 'Movimentação registrada', 'movement' => $movement]);
    }

    /**
     * 4. Fechamento Cego
     */
    public function close(Request $request)
    {
        // Tratamento em massa de vírgulas nos valores contados
        foreach (['counted_cash', 'counted_card', 'counted_pix'] as $field) {
            if ($request->has($field)) {
                $val = str_replace(['R$', ' ', ','], ['', '', '.'], $request->input($field));
                $request->merge([$field => $val]);
            }
        }

        $request->validate([
            'counted_cash' => 'required|numeric|min:0',
            'counted_card' => 'numeric|min:0',
            'counted_pix' => 'numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($request) {
            $employee = $request->user();
            $session = CashSession::where('employee_id', $employee->id)
                ->where('status', 'open')
                ->lockForUpdate()
                ->firstOrFail();

            // Totalizações (Ajuste a busca do PaymentMethod de acordo com o padrão de nomes no seu DB)
            $expectedCash = $session->opening_balance 
                          + $session->movements()->where('type', 'in')->sum('amount') 
                          - $session->movements()->where('type', 'out')->sum('amount');
                          
            $expectedCard = Payment::where('cash_session_id', $session->id)
                ->whereHas('order.paymentMethod', fn($q) => 
                    $q->where('name', 'like', '%Cartão%')
                      ->orWhere('display_name', 'like', '%Cartão%')
                      ->orWhere('name', 'like', '%Crédito%')
                      ->orWhere('name', 'like', '%Débito%')
                )->sum('amount');
                
            $expectedPix = Payment::where('cash_session_id', $session->id)
                ->whereHas('order.paymentMethod', fn($q) => 
                    $q->where('name', 'like', '%PIX%')
                      ->orWhere('display_name', 'like', '%PIX%')
                )->sum('amount');

            // Valores digitados
            $countedCash = (float) $request->input('counted_cash', 0);
            $countedCard = (float) $request->input('counted_card', 0);
            $countedPix  = (float) $request->input('counted_pix', 0);

            $totalExpected = $expectedCash + $expectedCard + $expectedPix;
            $totalCounted  = $countedCash + $countedCard + $countedPix;
            $totalDiff     = $totalCounted - $totalExpected;

            $session->update([
                'closed_at' => now(),
                'status' => 'closed',
                'calculated_balance' => $totalExpected,
                'closing_balance' => $totalCounted,
                'difference' => $totalDiff,
                'notes' => $request->notes,
            ]);

            return response()->json([
                'message' => 'Caixa fechado com sucesso',
                'data' => [
                    'difference' => $totalDiff,
                    'expected_cash' => $expectedCash,
                    'counted_cash'  => $countedCash,
                    'cash_difference' => $countedCash - $expectedCash,
                    'pix_total' => $expectedPix,
                    'card_total' => $expectedCard,
                ]
            ]);
        });
    }
}