<div>
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                    {{ __('Pagamento') }}
                </h3>
                @if($order->total_paid > 0)
                    <a href="{{ route('employee.orders.refund', $order) }}" class="btn btn-link">
                        {{ __('Reembolso') }}
                    </a>
                @endif
            </div>
        </x-slot:header>
        <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
            <dl @class(['border-t border-slate-100 divide-y divide-slate-100 dark:border-white/5 dark:divide-white/5', '-mb-5' => $order->payment_status === \App\Enums\PaymentStatus::PAID])>
                <div class="p-4 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-slate-500 dark:text-slate-400">
                        {{ __('Subtotal') }}
                    </dt>
                    <dd class="mt-1 flex text-sm leading-6 text-slate-600 sm:col-span-2 sm:mt-0 dark:text-slate-400">
                        <div class="flex-grow">
                            {{ trans_choice(':count item|:count itens', $this->total_order_items_quantity) }}
                        </div>
                        <div class="ml-4 flex-shrink-0 text-right tabular-nums">
                            <x-money :amount="$order->subtotal" :currency="config('app.currency')" />
                        </div>
                    </dd>
                </div>

                <div class="p-4 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium leading-6 text-slate-500 dark:text-slate-400">
                        {{ __('Frete') }}
                    </dt>
                    <dd class="mt-1 flex text-sm leading-6 text-slate-600 sm:col-span-2 sm:mt-0 dark:text-slate-400">
                        <div class="flex-grow">
                            {{ $order->shipping_rate }}
                        </div>
                        <div class="ml-4 flex-shrink-0 text-right tabular-nums">
                            <x-money :amount="$order->shipping_price" :currency="config('app.currency')" />
                        </div>
                    </dd>
                </div>

                <div class="flex items-center justify-between p-4 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-semibold text-primary dark:text-slate-200">
                        {{ __('Total') }}
                    </dt>
                    <dd class="text-sm font-bold text-primary tabular-nums sm:col-span-2 sm:text-right dark:text-white">
                        <x-money :amount="$order->total" :currency="config('app.currency')" />
                    </dd>
                </div>
                <div class="p-4 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        {{ __('Pago pelo Cliente') }}
                    </dt>
                    <dd class="mt-1 flex text-sm leading-6 text-slate-600 sm:col-span-2 sm:mt-0 dark:text-slate-400">
                        <div class="flex-grow">
                            {{ $order->paymentMethod->name }}
                        </div>
                        <div class="ml-4 flex-shrink-0 text-right font-semibold text-primary tabular-nums dark:text-slate-200">
                            <x-money :amount="$order->total_paid" :currency="config('app.currency')" />
                        </div>
                    </dd>
                </div>
                @if($order->refunds->count())
                    <div class="p-4 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium leading-6 text-slate-500 dark:text-slate-400">
                            {{ __('Reembolso') }}
                        </dt>
                        <dd class="mt-1 flex text-sm leading-6 text-slate-600 sm:col-span-2 sm:mt-0 dark:text-slate-400">
                            <div class="flex-grow">
                                <ul>
                                    @foreach($order->refunds as $refund)
                                        <li>{{ __('Motivo:') }} {!! $refund->reason ?? '&mdash;' !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="ml-4 flex-shrink-0 text-right tabular-nums text-red-600 dark:text-red-400">
                                <ul>
                                    @foreach($order->refunds as $refund)
                                        <li>
                                            <x-money :amount="-$refund->amount" :currency="config('app.currency')" />
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </dd>
                    </div>
                    <div class="p-4 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-semibold text-primary dark:text-slate-200">
                            {{ __('Pagamento Líquido') }}
                        </dt>
                        <dd class="mt-1 text-sm font-bold text-primary sm:mt-0 sm:col-span-2 sm:text-right tabular-nums dark:text-white">
                            <x-money :amount="$order->total_paid - $order->total_refunded" :currency="config('app.currency')" />
                        </dd>
                    </div>
                @endif
            </dl>
        </x-slot:content>

        @if($order->payment_status !== \App\Enums\PaymentStatus::PAID && ! $order->refunds->count())
            <x-slot:footer class="bg-slate-50/60 dark:bg-white/5">
                @error('verificacao')
                    <div class="mb-3 rounded-xl border border-red-200 bg-red-50 p-3 text-xs text-red-700 dark:border-red-900 dark:bg-red-900/20 dark:text-red-300">
                        {{ $message }}
                    </div>
                @enderror
                <div class="flex justify-end">
                    @if($order->paymentMethod->is_third_party)
                        <button
                            wire:click="verificarPagamentoMercadoPago"
                            wire:target="verificarPagamentoMercadoPago"
                            wire:loading.attr="disabled"
                            class="btn btn-primary"
                        >
                            <span wire:loading.remove wire:target="verificarPagamentoMercadoPago">{{ __('Verificar pagamento no Mercado Pago') }}</span>
                            <span wire:loading wire:target="verificarPagamentoMercadoPago">{{ __('Consultando...') }}</span>
                        </button>
                    @else
                        <button
                            wire:click="confirmMarkingPaymentAsPaid"
                            wire:target="confirmingMarkingAsPaid"
                            wire:loading.attr="disabled"
                            class="btn btn-primary"
                        >
                            {{ __('Marcar como Pago') }}
                        </button>
                    @endif
                </div>
            </x-slot:footer>
        @endif
    </x-card>

    <x-modal-alert wire:model="confirmingMarkingAsPaid">
        <x-slot name="title">
            {{ __('Marcar como pago') }}
        </x-slot>
        <x-slot name="content">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Processado por') }}
                <span class="font-semibold text-primary dark:text-slate-200">{{ $order->paymentMethod->name }}</span>
            </p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Se você recebeu o pagamento manualmente marque este pedido como pago') }}
            </p>
        </x-slot>
        <x-slot name="footer">
            <button
                wire:click="markAsPaid"
                wire:target="markAsPaid"
                wire:loading.attr="disabled"
                class="btn btn-primary w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Marcar como pago') }}
            </button>
            <button x-on:click="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                {{ __('Cancelar') }}
            </button>
        </x-slot>
    </x-modal-alert>

    <x-modal-alert wire:model="confirmandoPagamentoVerificado">
        <x-slot name="title">
            {{ __('Pagamento confirmado no Mercado Pago') }}
        </x-slot>
        <x-slot name="content">
            @if($pagamentoVerificado)
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('ID do pagamento') }}: <span class="font-semibold text-primary dark:text-slate-200">{{ $pagamentoVerificado['payment_id'] }}</span>
                </p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Valor aprovado') }}: <span class="font-semibold text-primary dark:text-slate-200"><x-money :amount="$pagamentoVerificado['transaction_amount']" /></span>
                </p>
                @if($pagamentoVerificado['amount_diff'] > 0.01)
                    <p class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400">
                        {{ __('Atenção: o valor aprovado difere do total do pedido em') }} <x-money :amount="$pagamentoVerificado['amount_diff']" />.
                    </p>
                @endif
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Confirmado direto na API do Mercado Pago agora — não é um valor salvo localmente.') }}
                </p>
            @endif
        </x-slot>
        <x-slot name="footer">
            <button
                wire:click="confirmarPagamentoVerificado"
                wire:target="confirmarPagamentoVerificado"
                wire:loading.attr="disabled"
                class="btn btn-primary w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Confirmar pagamento') }}
            </button>
            <button x-on:click="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                {{ __('Cancelar') }}
            </button>
        </x-slot>
    </x-modal-alert>
</div>