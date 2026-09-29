<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Gera links wa.me (deep link do WhatsApp Web/app) com mensagem
 * pré-preenchida, para o botão de WhatsApp direto em Cliente e Pedido.
 *
 * Não depende de nenhuma integração oficial da API do WhatsApp — é só um
 * link https://wa.me/<telefone>?text=<mensagem>, que abre a conversa já
 * com o texto pronto. O envio em si continua manual, pelo atendente —
 * mesma decisão já registrada em engineering-principles.md sobre evitar
 * automação de disparo de WhatsApp pelo risco de banimento da conta.
 */
class WhatsAppLinkService
{
    /**
     * Retorna null quando o cliente não existe ou não tem telefone
     * (ou o telefone salvo é inválido) — nunca lança exceção, pra nunca
     * quebrar a tela de Cliente/Pedido por um dado legado ruim.
     */
    public function forOrder(Order $order): ?string
    {
        if (! $order->relationLoaded('customer') || ! $order->customer) {
            return null;
        }

        return $this->build($order->customer, $this->orderMessage($order));
    }

    public function forCustomer(Customer $customer): ?string
    {
        return $this->build($customer, $this->customerMessage($customer));
    }

    private function build(Customer $customer, string $message): ?string
    {
        $digits = $this->digitsOnly($customer);

        if (! $digits) {
            return null;
        }

        return 'https://wa.me/' . $digits . '?text=' . rawurlencode($message);
    }

    /**
     * wa.me exige só dígitos (código do país + número), sem "+", espaços
     * ou traços. Nunca lança: telefone ausente ou mal formatado (dado
     * legado) só faz o botão não aparecer, não quebra a página.
     */
    private function digitsOnly(Customer $customer): ?string
    {
        if (! $customer->getRawOriginal('phone')) {
            return null;
        }

        try {
            $phone = $customer->phone;
        } catch (\Throwable $e) {
            return null;
        }

        if (! $phone) {
            return null;
        }

        return preg_replace('/\D/', '', $phone->formatE164());
    }

    private function orderMessage(Order $order): string
    {
        return __('Olá :name, aqui é da Jumbonline sobre o pedido #:id.', [
            'name' => $this->firstName($order->customer->name),
            'id' => $order->id,
        ]);
    }

    private function customerMessage(Customer $customer): string
    {
        return __('Olá :name, aqui é da Jumbonline.', [
            'name' => $this->firstName($customer->name),
        ]);
    }

    private function firstName(string $fullName): string
    {
        return Str::of($fullName)->trim()->before(' ')->toString() ?: $fullName;
    }
}