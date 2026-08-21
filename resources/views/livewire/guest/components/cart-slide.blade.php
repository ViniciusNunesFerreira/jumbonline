<div>
    <x-slide-over wire:model="isShown">
        <x-slot:title>
            <span class="font-urbanist font-bold text-primary">Seu Pacote Jumbo</span>
        </x-slot:title>

        <x-slot:content>
            @if(($cart->weight ?? 0) > 0)
                @php
                    $weightMax = 12;
                    $weightPercent = min(100, (($cart->weight ?? 0) / $weightMax) * 100);
                @endphp
                <div class="mb-6 rounded-2xl border border-secondary bg-complement-500 p-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-primary">Peso do Jumbo</span>
                        <span class="text-slate-500">{{ number_format($cart->weight ?? 0, 2) }}kg de {{ $weightMax }}kg</span>
                    </div>
                    <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-white">
                        <div class="h-full rounded-full bg-accent transition-all duration-300" style="width: {{ $weightPercent }}%"></div>
                    </div>
                </div>
            @endif

            <div class="flow-root">
                <ul role="list" class="-my-6 divide-y divide-secondary/60">
                    @forelse($cartItems as $item)
                        <li wire:key="cart-item-id-{{ $item->id }}" class="flex py-6">
                            <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-secondary bg-complement-500">
                                <img
                                    src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image') : $item->product->getFirstMediaUrl('gallery', 'thumb_large') }}"
                                    alt="{{ $item->product->name }}"
                                    class="h-full w-full object-cover object-center"
                                    loading="lazy"
                                >
                            </div>

                            <div class="ml-4 flex flex-1 flex-col">
                                <div>
                                    <div class="flex justify-between gap-2">
                                        <h3 class="line-clamp-2 font-urbanist text-sm font-semibold text-primary">
                                            <a href="{{ route('guest.products.detail', $item->product) }}" class="hover:text-accent">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        <p class="flex-shrink-0 text-sm font-semibold text-primary">
                                            <x-money :amount="$item->price * $item->quantity" :currency="config('app.currency')" />
                                        </p>
                                    </div>
                                    @if($item->variant->variantAttributes->count())
                                        <p class="mt-1 text-xs text-slate-500">
                                            @foreach($item->variant->variantAttributes as $attribute)
                                                {{ $attribute->optionValue->label }}{{ !$loop->last ? ' · ' : '' }}
                                            @endforeach
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-2 flex flex-1 items-end justify-between">
                                    <div class="flex items-center gap-2 rounded-full border border-accent bg-accent/5 px-2 py-1">
                                        <button
                                            type="button"
                                            wire:click="decrementItem('{{ $item->id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="decrementItem('{{ $item->id }}')"
                                            class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-primary shadow-sm hover:bg-secondary/40 disabled:opacity-50"
                                        >−</button>
                                        <span class="w-4 text-center text-sm font-bold text-primary">{{ $item->quantity }}</span>
                                        <button
                                            type="button"
                                            wire:click="incrementItem('{{ $item->id }}')"
                                            @disabled($item->category && $item->quantity >= $item->category->quantity)
                                            wire:loading.attr="disabled"
                                            wire:target="incrementItem('{{ $item->id }}')"
                                            class="flex h-6 w-6 items-center justify-center rounded-full bg-accent text-white shadow-sm hover:bg-primary disabled:opacity-30"
                                        >+</button>
                                    </div>

                                    <button
                                        wire:click="removeCartItem('{{ $item->id }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="removeCartItem('{{ $item->id }}')"
                                        type="button"
                                        class="text-xs text-slate-400 underline hover:text-accent disabled:opacity-50"
                                    >
                                        {{ __('Remover') }}
                                    </button>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="flex flex-col items-center justify-center py-10 text-center">
                            <img src="{{ asset('img/maskote.png') }}" alt="" class="h-32 w-auto opacity-90">
                            <h3 class="mt-4 font-urbanist text-lg font-semibold text-primary">
                                {{ __('Seu Jumbo ainda está vazio') }}
                            </h3>
                            <p class="mt-1 max-w-xs text-sm text-slate-500">
                                Escolha os produtos dentro das normas da unidade para começar a montar o envio.
                            </p>
                            <div class="mt-6">
                                <button x-on:click="show = false" type="button" class="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:bg-primary">
                                    {{ __('Escolher produtos') }}
                                </button>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </x-slot:content>

        @if(count($cartItems))
            <x-slot:footer>
                <div class="flex justify-between text-base font-medium text-primary">
                    <p>{{ __('Subtotal') }}</p>
                    <p><x-money :amount="$cart->subtotal ?? 0" :currency="config('app.currency')" /></p>
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ __('Frete calculado na finalização, direto para a unidade.') }}</p>

                <div class="mt-6">
                    
                    <a href="{{ route('guest.checkout') }}"
                        class="flex w-full items-center justify-center gap-2 rounded-full bg-accent py-4 text-base font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary"
                    >
                        {{ __('Finalizar Pedido') }}
                        <x-heroicon-s-arrow-right class="h-4 w-4" />
                    </a>
                    <p class="mt-2 flex items-center justify-center gap-1 text-xs text-slate-400">
                        <x-heroicon-s-shield-check class="h-3.5 w-3.5 text-accent" />
                        Pagamento processado com segurança
                    </p>
                </div>

                <div class="mt-4 flex justify-center text-center text-sm text-slate-500">
                    <button x-on:click="show = false" type="button" class="underline hover:text-accent">
                        {{ __('Continuar escolhendo') }}
                    </button>
                </div>
            </x-slot:footer>
        @endif
    </x-slide-over>
</div>