<?php

namespace App\Http\Livewire\Employee\Order\Components;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MercadoPagoPaymentVerificationService;
use Livewire\Component;

class OrderPaymentDetail extends Component
{
    public Order $order;

    public bool $confirmingMarkingAsPaid = false;

    public bool $verificandoPagamento = false;

    public ?array $pagamentoVerificado = null;

    public bool $confirmandoPagamentoVerificado = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->order->load(['orderItems', 'orderDiscounts.orderItem', 'paymentMethod:id,name,is_third_party', 'refunds']);
    }

    public function confirmMarkingPaymentAsPaid()
    {
        $this->confirmingMarkingAsPaid = true;
    }

    public function markAsPaid(): void
    {
        $payment = new Payment([
            'amount' => $this->order->total,
            'currency' => config('app.currency'),
            'method' => $this->order->paymentMethod->name,
            'status' => 'PAID',
        ]);

        $this->order->payments()->save($payment);

        $this->order->payment_status = PaymentStatus::PAID;

        $this->order->save();

        $this->emit('refresh')->self();

        $this->emit('refresh')->up();

        $this->confirmingMarkingAsPaid = false;

        $this->notify(trans('Payment marked as paid.'));
    }

    /**
     * Consulta a API real do Mercado Pago pra este pedido — nunca marca
     * nada como pago sozinho, só traz o resultado pra confirmação.
     */
    public function verificarPagamentoMercadoPago(MercadoPagoPaymentVerificationService $service)
    {
        $this->resetErrorBag('verificacao');
        $this->verificandoPagamento = true;

        try {
            $resultado = $service->verificar($this->order);
        } catch (\Throwable $e) {
            $this->verificandoPagamento = false;
            $this->addError('verificacao', 'Não foi possível consultar o Mercado Pago agora: ' . $e->getMessage());
            return;
        }

        $this->verificandoPagamento = false;

        if (! $resultado['confirmado']) {
            $this->addError('verificacao', 'O Mercado Pago não confirma este pedido como aprovado agora. Nenhuma alteração foi feita.');
            return;
        }

        $this->pagamentoVerificado = $resultado;
        $this->confirmandoPagamentoVerificado = true;
    }

    /**
     * Só aplica o que já foi verificado — não consulta a API de novo aqui de
     * propósito, pra não haver janela entre o que o atendente viu na tela e
     * o que é de fato aplicado.
     */
    public function confirmarPagamentoVerificado(MercadoPagoPaymentVerificationService $service)
    {
        if (! $this->pagamentoVerificado) {
            return;
        }

        $service->confirmarPagamento($this->order, $this->pagamentoVerificado);

        $this->confirmandoPagamentoVerificado = false;
        $this->pagamentoVerificado = null;

        $this->emit('refresh')->self();
        $this->emit('refresh')->up();

        $this->notify(trans('Pagamento confirmado com o Mercado Pago.'));
    }

    public function getTotalOrderItemsQuantityProperty()
    {
        return $this->order->orderItems->sum(function ($item) {
            return $item->quantity;
        });
    }

    public function render()
    {
        return view('livewire.employee.order.components.order-payment-detail');
    }
}