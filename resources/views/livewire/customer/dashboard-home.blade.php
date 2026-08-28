<div>
    <x-slot:title>Minha Conta</x-slot:title>

    <x-account-layout active="dashboard" title="Olá, {{ explode(' ', $this->customer->name)[0] }}!" subtitle="Aqui está um resumo da sua conta.">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <h2 class="font-urbanist text-lg font-bold text-primary">Último Pedido</h2>
                        @if($this->lastOrder)
                            <a href="{{ route('customer.orders.detail', $this->lastOrder) }}" class="text-sm font-semibold text-purple hover:text-accent">Ver detalhes</a>
                        @endif
                    </div>

                    @if($this->lastOrder)
                        @php
                            $order = $this->lastOrder;
                            $paid = in_array($order->payment_status, [\App\Enums\PaymentStatus::PAID, \App\Enums\PaymentStatus::AUTHORIZED]);
                            $shipped = $order->shipping_status === \App\Enums\ShippingStatus::SHIPPED;
                        @endphp
                        <div class="mt-4 flex items-center gap-4">
                            <div class="flex -space-x-3">
                                @foreach($order->orderItems->take(3) as $item)
                                    <div class="h-12 w-12 overflow-hidden rounded-xl border-2 border-white bg-complement-500 shadow-sm">
                                        <img src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image') : $item->product->getFirstMediaUrl('gallery') }}" alt="" class="h-full w-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <div>
                                <p class="text-sm font-medium text-primary">Pedido #{{ $order->id }} · {{ $order->orderItems->count() }} {{ $order->orderItems->count() == 1 ? 'item' : 'itens' }}</p>
                                <p class="text-xs text-slate-500">{{ $order->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center gap-2">
                            @foreach([true, $paid, $paid, $shipped] as $done)
                                <div class="h-1.5 flex-1 rounded-full {{ $done ? 'bg-accent' : 'bg-secondary' }}"></div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-slate-500">
                            {{ $shipped ? 'Enviado' : ($paid ? 'Em separação' : 'Aguardando pagamento') }}
                        </p>

                        @if($order->payment_status === \App\Enums\PaymentStatus::UNPAID)
                            <a href="{{ route('customer.order.payment', $order->id) }}" class="mt-5 inline-flex items-center gap-2 rounded-full bg-accent px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary">
                                Efetuar Pagamento <x-heroicon-s-arrow-right class="h-4 w-4" />
                            </a>
                        @endif
                    @else
                        <div class="mt-4 py-8 text-center">
                            <img src="{{ asset('img/maskote.png') }}" alt="" class="mx-auto h-24 w-auto opacity-90">
                            <p class="mt-3 text-sm text-slate-500">Você ainda não fez nenhum pedido.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('guest.welcome') }}" class="flex items-center gap-3 rounded-3xl border border-secondary bg-white p-5 transition-colors hover:border-accent">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-accent/10">
                        <x-heroicon-s-plus class="h-5 w-5 text-accent" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-primary">Montar novo Jumbo</p>
                        <p class="text-xs text-slate-500">Comece um pedido novo</p>
                    </div>
                </a>
                <a href="{{ route('customer.orders.list') }}" class="flex items-center gap-3 rounded-3xl border border-secondary bg-white p-5 transition-colors hover:border-accent">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-accent/10">
                        <x-heroicon-s-shopping-bag class="h-5 w-5 text-accent" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-primary">{{ $this->ordersCount }} {{ $this->ordersCount == 1 ? 'pedido' : 'pedidos' }}</p>
                        <p class="text-xs text-slate-500">Ver histórico completo</p>
                    </div>
                </a>
                <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 rounded-3xl border border-secondary bg-white p-5 transition-colors hover:border-accent">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-accent/10">
                        <x-heroicon-s-user class="h-5 w-5 text-accent" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-primary">Meu Perfil</p>
                        <p class="text-xs text-slate-500">Dados e senha</p>
                    </div>
                </a>
            </div>
        </div>
    </x-account-layout>
</div>