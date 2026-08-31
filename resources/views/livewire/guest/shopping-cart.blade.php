<div>
    <x-slot:title>{{ __('Seu Jumbo') }}</x-slot:title>

    <div class="bg-complement-500 py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">

            <h1 class="text-center font-urbanist text-2xl font-bold text-primary sm:text-3xl">
                {{ __('Seu Jumbo') }}
            </h1>

            @if($this->prisonUnit)
                <p class="mt-2 flex items-center justify-center gap-1.5 text-center text-sm text-slate-500">
                    <x-heroicon-s-map-pin class="h-4 w-4 text-accent" />
                    Montado para <span class="font-semibold text-primary">{{ $this->prisonUnit->name }}</span>
                </p>
            @endif

            @unless($cartItems->count())
                <div class="mt-10 rounded-3xl border border-secondary bg-white p-12 text-center">
                    <img src="{{ asset('img/maskote.png') }}" alt="" class="mx-auto h-32 w-auto opacity-90">
                    <h3 class="mt-4 font-urbanist text-lg font-semibold text-primary">
                        {{ __('Seu Jumbo ainda está vazio') }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ __('Escolha os produtos dentro das normas da unidade para começar a montar o envio.') }}
                    </p>
                    <a href="{{ route('guest.products.list', $prison) }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:bg-primary">
                        {{ __('Escolher produtos') }} <x-heroicon-s-arrow-right class="h-4 w-4" />
                    </a>
                </div>
            @else
                @php
                    $weightMax = 12;
                    $weightPercent = min(100, (($cart->weight ?? 0) / $weightMax) * 100);
                @endphp
                <div class="mt-6 rounded-2xl border border-secondary bg-white p-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-primary">Peso do Jumbo</span>
                        <span class="text-slate-500">{{ number_format($cart->weight ?? 0, 2) }}kg de {{ $weightMax }}kg</span>
                    </div>
                    <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-complement-500">
                        <div class="h-full rounded-full bg-accent transition-all duration-300" style="width: {{ $weightPercent }}%"></div>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-3xl border border-secondary bg-white">
                    <ul role="list" class="divide-y divide-secondary/60">
                        @foreach($cartItems as $item)
                            <li wire:key="cart-page-item-{{ $item->id }}" class="flex gap-4 p-5 sm:p-6">
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-secondary bg-complement-500 sm:h-24 sm:w-24">
                                    @if($item->variant->hasMedia('image'))
                                        {{ $item->variant->getFirstMedia('image')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover']) }}
                                    @elseif($item->product->hasMedia('gallery'))
                                        {{ $item->product->getFirstMedia('gallery')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover']) }}
                                    @else
                                        <x-heroicon-o-camera class="h-full w-full p-6 text-slate-400" />
                                    @endif
                                </div>

                                <div class="flex flex-1 flex-col">
                                    <div class="flex justify-between gap-2">
                                        <h3 class="font-urbanist text-sm font-semibold text-primary">{{ $item->product->name }}</h3>
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

                                    <div class="mt-3 flex flex-1 items-end justify-between">
                                        <div class="flex items-center gap-2 rounded-full border border-accent bg-accent/5 px-2 py-1">
                                            <button
                                                type="button"
                                                wire:click="decrementItem({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="decrementItem({{ $item->id }})"
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-primary shadow-sm hover:bg-secondary/40 disabled:opacity-50"
                                            >−</button>
                                            <span class="w-4 text-center text-sm font-bold text-primary">{{ $item->quantity }}</span>
                                            <button
                                                type="button"
                                                wire:click="incrementItem({{ $item->id }})"
                                                @disabled($item->category && $item->quantity >= $item->category->quantity)
                                                wire:loading.attr="disabled"
                                                wire:target="incrementItem({{ $item->id }})"
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-accent text-white shadow-sm hover:bg-primary disabled:opacity-30"
                                            >+</button>
                                        </div>

                                        <button
                                            wire:click.prevent="removeCartItem({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="removeCartItem({{ $item->id }})"
                                            type="button"
                                            class="text-xs text-slate-400 underline hover:text-accent disabled:opacity-50"
                                        >
                                            {{ __('Remover') }}
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6 rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                    <div class="flex justify-between text-base font-medium text-primary">
                        <p>{{ __('Subtotal') }}</p>
                        <p><x-money :amount="$cart->subtotal" :currency="config('app.currency')" /></p>
                    </div>
                    <p class="mt-1 text-sm text-slate-500">{{ __('Frete e impostos serão calculados na finalização da compra.') }}</p>

                    <a href="{{ route('guest.checkout') }}" class="mt-6 flex w-full items-center justify-center gap-2 rounded-full bg-accent py-4 text-base font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">
                        {{ __('Finalizar Pedido') }} <x-heroicon-s-arrow-right class="h-4 w-4" />
                    </a>

                    <div class="mt-4 text-center">
                        <a href="{{ route('guest.products.list', $prison) }}" class="text-sm text-slate-500 underline hover:text-accent">
                            {{ __('Continuar escolhendo') }}
                        </a>
                    </div>
                </div>
            @endunless
        </div>
    </div>
</div>