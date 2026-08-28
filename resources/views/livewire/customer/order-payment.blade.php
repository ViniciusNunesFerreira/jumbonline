<div x-data="{ showError: false, errorMessage: '' }" x-on:payment-error.document="showError = true; errorMessage = $event.detail.message">
    <x-slot:title>{{ __('Pedido - :orderId', ['orderId' => $order->id]) }}</x-slot:title>

    <div class="bg-complement-500 w-full py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">

            <div class="mb-6 flex items-center justify-center gap-2 text-sm font-semibold text-purple">
                <x-heroicon-s-lock-closed class="h-4 w-4 text-accent" /> Etapa final — pagamento seguro
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

                <!-- Pagamento - foco principal -->
                <div class="order-2 lg:order-1 lg:col-span-3">
                    <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                        <div @class([
                            'flex items-start gap-3 rounded-2xl p-4 text-sm',
                            'bg-complement-500 text-slate-600' => $order->payment_status === \App\Enums\PaymentStatus::UNPAID,
                            'bg-warning/10 text-primary' => $order->payment_status === \App\Enums\PaymentStatus::PENDING,
                            'bg-success/10 text-primary' => !in_array($order->payment_status, [\App\Enums\PaymentStatus::UNPAID, \App\Enums\PaymentStatus::PENDING]),
                        ])>
                            @if($order->payment_status === \App\Enums\PaymentStatus::UNPAID)
                                <x-heroicon-s-credit-card class="h-5 w-5 flex-shrink-0 text-accent" />
                                <span>Escolha uma das opções abaixo pra finalizar seu pedido.</span>
                            @elseif($order->payment_status === \App\Enums\PaymentStatus::PENDING)
                                <x-heroicon-s-clock class="h-5 w-5 flex-shrink-0 text-warning" />
                                <span>Aguardando confirmação — avisaremos assim que for aprovado.</span>
                            @else
                                <x-heroicon-s-check-circle class="h-5 w-5 flex-shrink-0 text-success" />
                                <span>Pagamento recebido! Já estamos processando seu jumbo.</span>
                            @endif
                        </div>

                        <h2 class="mt-6 font-urbanist text-lg font-bold text-primary">Forma de Pagamento</h2>
                        <div id="paymentBrick_container" class="mt-4"></div>
                        <div id="statusScreenBrick_container"></div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs text-slate-400">
                        <span class="flex items-center gap-1.5"><x-heroicon-s-shield-check class="h-4 w-4 text-accent" /> Pagamento seguro via Mercado Pago</span>
                        <span class="flex items-center gap-1.5"><x-heroicon-s-lock-closed class="h-4 w-4 text-accent" /> Dados criptografados</span>
                    </div>
                </div>

                <!-- Resumo - painel de apoio -->
                <div class="order-1 lg:order-2 lg:col-span-2">
                    <div class="sticky top-6 rounded-3xl border border-secondary bg-white p-6">
                        <div class="flex items-center gap-2 text-accent">
                            <x-heroicon-s-shopping-bag class="h-5 w-5" />
                            <span class="font-urbanist text-sm font-bold uppercase tracking-wide">Pedido #{{ $order->id }}</span>
                        </div>

                        <dl class="mt-4 space-y-3 border-t border-secondary pt-4 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Subtotal</dt>
                                <dd class="font-medium text-primary"><x-money :amount="$order->subtotal" :currency="config('app.currency')" /></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Envio</dt>
                                <dd class="font-medium text-primary"><x-money :amount="$order->shipping_price" :currency="config('app.currency')" /></dd>
                            </div>
                            <div class="flex justify-between border-t border-secondary pt-3 text-base">
                                <dt class="font-bold text-primary">Total</dt>
                                <dd class="font-bold text-primary"><x-money :amount="$order->total - $order->total_refunded" :currency="config('app.currency')" /></dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex flex-col items-center gap-2 border-t border-secondary pt-6 text-center">
                            <img src="{{ asset('img/mascote-logo-mark.png') }}" alt="" class="h-16 w-auto opacity-90">
                            <p class="text-xs text-slate-400">Quase lá! Assim que confirmar, cuidamos do resto.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de erro -->
    <div x-show="showError" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-primary/40 p-4">
        <div class="w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-xl">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-warning/10">
                <x-heroicon-s-exclamation-triangle class="h-6 w-6 text-warning" />
            </div>
            <h3 class="mt-4 font-urbanist text-lg font-bold text-primary">Não foi possível processar</h3>
            <p class="mt-2 text-sm text-slate-500" x-text="errorMessage"></p>
            <button type="button" @click="showError = false" class="mt-6 w-full rounded-full bg-accent py-3 text-sm font-semibold text-white hover:bg-primary">
                Tentar novamente
            </button>
        </div>
    </div>

    <script>
        const mp = new MercadoPago('{{ $this->mercadopago->meta['public_key'] }}', { locale: 'pt-BR' });
        const bricksBuilder = mp.bricks();

        const renderPaymentBrick = async (bricksBuilder) => {
            const settings = {
                initialization: {
                    amount: parseFloat('{{$order->total}}'),
                },
                customization: {
                    paymentMethods: {
                        creditCard: "all",
                        ticket: "bolbradesco",
                        bankTransfer: "pix",
                        maxInstallments: 12,
                    },
                    visual: {
                        style: {
                            customVariables: {
                                textPrimaryColor: "#1B1850",
                                baseColor: "#F1598F"
                            },
                        },
                    }
                },
                callbacks: {
                    onReady: () => {},
                    onSubmit: ({ selectedPaymentMethod, formData }) => {
                        return new Promise((resolve, reject) => {
                            fetch("{{ route('customer.purchase.post', $order) }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(formData),
                            })
                            .then((response) => response.json())
                            .then((response) => {
                                if (!response.id) {
                                    console.error('Erro ao criar pagamento:', response);
                                    document.dispatchEvent(new CustomEvent('payment-error', {
                                        detail: { message: response.message === 'excludes_by_rule'
                                            ? 'Este método de pagamento não pôde ser processado. Tente outro cartão ou forma de pagamento.'
                                            : (response.message || 'Verifique os dados e tente novamente.') }
                                    }));
                                    reject();
                                    return;
                                }

                                const renderStatusScreenBrick = async (bricksBuilder) => {
                                    const settings = {
                                        initialization: { paymentId: response.id },
                                        customization: {
                                            visual: {
                                                style: {
                                                    textPrimaryColor: "#1B1850",
                                                    baseColor: "#F1598F"
                                                }
                                            },
                                            backUrls: { 'return': '{{route("customer.orders.list")}}' }
                                        },
                                        callbacks: {
                                            onReady: () => {
                                                document.getElementById('paymentBrick_container').style.display = 'none';
                                            },
                                            onError: (error) => console.error(error),
                                        },
                                    };
                                    window.statusScreenBrickController = await bricksBuilder.create('statusScreen', 'statusScreenBrick_container', settings);
                                };

                                renderStatusScreenBrick(bricksBuilder);
                                resolve();
                            })
                            .catch((error) => {
                                console.error("erro " + error);
                                document.dispatchEvent(new CustomEvent('payment-error', {
                                    detail: { message: 'Erro de conexão. Verifique sua internet e tente novamente.' }
                                }));
                                reject();
                            });
                        });
                    },
                    onError: (error) => console.error(error),
                },
            };
            window.paymentBrickController = await bricksBuilder.create("payment", "paymentBrick_container", settings);
        };

        renderPaymentBrick(bricksBuilder);
    </script>
</div>

@push('script_header')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
@endpush