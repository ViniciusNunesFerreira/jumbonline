<div>
    <x-slot:title>{{ __('Pedido #:orderId', ['orderId' => $order->id]) }}</x-slot:title>

    <x-account-layout active="orders">
        <x-slot:header>
            <a href="{{ route('customer.orders.list') }}" class="flex items-center gap-1.5 text-sm font-semibold text-purple hover:text-accent">
                <x-heroicon-s-chevron-left class="h-4 w-4" /> Voltar aos pedidos
            </a>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                <h1 class="font-urbanist text-2xl font-bold text-primary sm:text-3xl">Pedido #{{ $order->id }}</h1>
                @if($order->payment_status === \App\Enums\PaymentStatus::UNPAID)
                    <a href="{{ route('customer.order.payment', $order->id) }}" class="rounded-full bg-accent px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">Efetuar Pagamento</a>
                @endif
            </div>
            <p class="mt-1 text-sm text-slate-500">Feito em {{ $order->created_at->format('d/m/Y') }}</p>
        </x-slot:header>

        @php
            $paid = in_array($order->payment_status, [\App\Enums\PaymentStatus::PAID, \App\Enums\PaymentStatus::AUTHORIZED]);
            $shipped = $order->shipping_status === \App\Enums\ShippingStatus::SHIPPED;
            $steps = [
                ['label' => 'Pedido realizado', 'icon' => 'shopping-bag', 'done' => true],
                ['label' => 'Pagamento confirmado', 'icon' => 'credit-card', 'done' => $paid],
                ['label' => 'Em separação', 'icon' => 'archive-box', 'done' => $paid],
                ['label' => 'Enviado', 'icon' => 'truck', 'done' => $shipped],
            ];
        @endphp

        <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
            <div class="grid grid-cols-4 gap-2">
                @foreach($steps as $index => $step)
                    <div class="flex flex-col items-center text-center">
                        <div class="flex w-full items-center">
                            <div class="h-0.5 flex-1 {{ $index === 0 ? 'invisible' : ($step['done'] ? 'bg-accent' : 'bg-secondary') }}"></div>
                            <div @class(['flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full', 'bg-accent text-white' => $step['done'], 'bg-secondary/50 text-slate-400' => !$step['done']])>
                                @if($step['icon'] === 'shopping-bag') <x-heroicon-s-shopping-bag class="h-4 w-4" />
                                @elseif($step['icon'] === 'credit-card') <x-heroicon-s-credit-card class="h-4 w-4" />
                                @elseif($step['icon'] === 'archive-box') <x-heroicon-s-archive-box class="h-4 w-4" />
                                @else <x-heroicon-s-truck class="h-4 w-4" />
                                @endif
                            </div>
                            <div class="h-0.5 flex-1 {{ $index === count($steps) - 1 ? 'invisible' : ($steps[$index + 1]['done'] ? 'bg-accent' : 'bg-secondary') }}"></div>
                        </div>
                        <span @class(['mt-2 text-xs font-medium', 'text-primary' => $step['done'], 'text-slate-400' => !$step['done']])>{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>

            @if($shipped && $order->shipments->isNotEmpty())
                <div class="mt-6 space-y-2 border-t border-secondary pt-6">
                    @foreach($order->shipments as $shipment)
                        <div class="flex flex-wrap items-center justify-between gap-2 rounded-2xl bg-complement-500 p-4 text-sm">
                            <div class="flex items-center gap-2">
                                <x-heroicon-s-truck class="h-4 w-4 text-accent" />
                                <span class="text-slate-600">{{ $shipment->shipping_carrier?->label() ?? 'Transportadora' }}</span>
                                @if($shipment->tracking_number)<span class="font-semibold text-primary">{{ $shipment->tracking_number }}</span>@endif
                            </div>
                            @if($shipment->tracking_url)
                                <a href="{{ $shipment->tracking_url }}" target="_blank" rel="noopener" class="flex items-center gap-1 font-semibold text-purple hover:text-accent">Rastrear envio <x-heroicon-s-arrow-top-right-on-square class="h-3.5 w-3.5" /></a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-6 rounded-3xl border border-secondary bg-white p-6 sm:p-8">
            <h2 class="font-urbanist text-lg font-bold text-primary">Itens do Jumbo</h2>
            <ul role="list" class="mt-4 divide-y divide-secondary/60">
                @foreach($order->orderItems as $item)
                    <li class="flex items-center gap-4 py-4">
                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-secondary bg-complement-500">
                            @if($item->variant->hasMedia('image'))
                                {{ $item->variant->getFirstMedia('image')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover']) }}
                            @elseif($item->product->hasMedia('gallery'))
                                {{ $item->product->getFirstMedia('gallery')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover']) }}
                            @else
                                <x-heroicon-o-camera class="h-full w-full p-4 text-slate-400" />
                            @endif
                        </div>
                        <div class="flex-1"><p class="text-sm font-medium text-primary">{{ $item->quantity }}x {{ $item->product->name }}</p></div>
                        <div class="text-sm font-semibold text-primary"><x-money :amount="$item->price" :currency="config('app.currency')" /></div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="rounded-3xl border border-secondary bg-white p-6">
                <h3 class="flex items-center gap-2 font-urbanist text-sm font-bold uppercase tracking-wide text-accent"><x-heroicon-s-user class="h-4 w-4" /> Detento</h3>
                <div class="mt-3 space-y-1 text-sm text-slate-600">
                    <p class="font-medium text-primary">{{ $this->detentoSnapshot->name ?? '—' }}</p>
                    <p>Matrícula: {{ $this->detentoSnapshot->matricula ?? '—' }}</p>
                    <p>Raio {{ $this->detentoSnapshot->raio ?? '—' }} · Cela {{ $this->detentoSnapshot->cela ?? '—' }}</p>
                </div>
            </div>
            <div class="rounded-3xl border border-secondary bg-white p-6">
                <h3 class="flex items-center gap-2 font-urbanist text-sm font-bold uppercase tracking-wide text-accent"><x-heroicon-s-identification class="h-4 w-4" /> Visitante</h3>
                <div class="mt-3 space-y-1 text-sm text-slate-600">
                    <p class="font-medium text-primary">{{ $this->billingAddress->nome ?? '—' }}</p>
                    <p>{{ $this->billingAddress->logradouro ?? '' }}{{ isset($this->billingAddress->numero) ? ', '.$this->billingAddress->numero : '' }}</p>
                    <p>{{ $this->billingAddress->bairro ?? '' }} — {{ $this->billingAddress->cidade ?? '' }}/{{ $this->billingAddress->uf ?? '' }}</p>
                    @if($this->billingAddress->phone ?? null)<p>{{ $this->billingAddress->phone }}</p>@endif
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-3xl border border-secondary bg-white p-6 sm:p-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <h3 class="flex items-center gap-2 font-urbanist text-sm font-bold uppercase tracking-wide text-accent"><x-heroicon-s-map-pin class="h-4 w-4" /> Unidade de Entrega</h3>
                    <div class="mt-3 space-y-1 text-sm text-slate-600">
                        <p class="font-medium text-primary">{{ $this->shippingAddress->name ?? '—' }}</p>
                        <p>{{ $this->shippingAddress->logradouro ?? '' }} — {{ $this->shippingAddress->cidade ?? '' }}/{{ $this->shippingAddress->uf ?? '' }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="flex items-center gap-2 font-urbanist text-sm font-bold uppercase tracking-wide text-accent"><x-heroicon-s-credit-card class="h-4 w-4" /> Pagamento</h3>
                    <div class="mt-3 space-y-1 text-sm text-slate-600">
                        <p>{{ $order->paymentMethod->name }}</p>
                        <p>Frete: {{ $order->shipping_rate }}</p>
                    </div>
                </div>
            </div>

            <dl class="mt-6 space-y-3 border-t border-secondary pt-6 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd class="font-medium text-primary"><x-money :amount="$order->subtotal" :currency="config('app.currency')" /></dd></div>
                @if($order->discount_total > 0)
                    <div class="flex justify-between"><dt class="text-warning">Desconto</dt><dd class="text-warning">− <x-money :amount="$order->discount_total" :currency="config('app.currency')" /></dd></div>
                @endif
                <div class="flex justify-between"><dt class="text-slate-500">Envio</dt><dd class="font-medium text-primary"><x-money :amount="$order->shipping_price" :currency="config('app.currency')" /></dd></div>
                <div class="flex justify-between border-t border-secondary pt-3 text-base"><dt class="font-bold text-primary">Total</dt><dd class="font-bold text-primary"><x-money :amount="$order->total - $order->total_refunded" :currency="config('app.currency')" /></dd></div>
            </dl>
        </div>
    </x-account-layout>

    <form wire:submit.prevent="saveReview">
        <x-modal-dialog wire:model="showReviewForm">
            <x-slot:title>Escreva uma avaliação</x-slot:title>
            <x-slot:content>
                <div class="space-y-5">
                    <div>
                        <x-input-label for="review.rating" value="Avaliação" />
                        <x-select wire:model.defer="review.rating" id="rating" class="mt-1.5 block w-full">
                            <option value="">Classifique o produto</option>
                            @for($i = 1; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'estrela' : 'estrelas' }}</option>@endfor
                        </x-select>
                        <x-input-error for="review.rating" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="review.title" value="Resumo" />
                        <x-input wire:model.defer="review.title" id="title" type="text" class="mt-1.5 block w-full" />
                        <x-input-error for="review.title" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="review.content" value="Sua opinião" />
                        <x-textarea wire:model="review.content" id="comment" class="mt-1.5 block w-full" />
                        <x-input-error for="review.content" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button wire:target="save" wire:loading.attr="disabled" type="submit" class="w-full rounded-full bg-accent py-3 text-sm font-semibold text-white hover:bg-primary sm:ml-3 sm:w-auto sm:px-8">Salvar</button>
                <button x-on:click="show = false" wire:target="save" wire:loading.attr="disabled" type="button" class="mt-3 w-full rounded-full border border-secondary py-3 text-sm font-semibold text-primary hover:bg-complement-500 sm:mt-0 sm:w-auto sm:px-8">Cancelar</button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>