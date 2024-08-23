<div>
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:px-0">
            @unless($cartItems->count())
                <div class="mb-6 mx-auto text-center">
                    <x-heroicon-o-shopping-cart class="mx-auto h-24 w-24 text-slate-400" />

                    <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">
                        {{ __('Seu carrinho de compras está vazio no momento') }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Antes de prosseguir para a finalização da compra você deve adicionar alguns produtos ao seu carrinho de compras.') }}
                    </p>

                    <div class="mt-6">
                        <a
                            href="{{ route('guest.products.list', $prison) }}"
                            class="btn btn-primary"
                        >
                            {{ __('Continue comprando') }}
                        </a>
                    </div>
                </div>
            @else
                <h1 class="text-center text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    {{ __('Lista Jumbo') }}
                </h1>

                <div class="mt-12">
                    <section aria-labelledby="cart-heading">
                        <h2
                            id="cart-heading"
                            class="sr-only"
                        >
                            {{ __('Itens em seu carrinho de compras') }}
                        </h2>

                        <ul
                            role="list"
                            class="divide-y divide-slate-200 border-b border-t border-slate-200"
                        >
                            @foreach($cartItems as $item)
                                <li class="flex py-6">
                                    <div class="flex-shrink-0 border border-slate-200 rounded-md">
                                        @if($item->variant->hasMedia('image'))
                                            {{ $item->variant->getFirstMedia('image')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-24 w-24 rounded-md object-cover object-center sm:h-32 sm:w-32']) }}
                                        @elseif($item->product->hasMedia('gallery'))
                                            {{ $item->product->getFirstMedia('gallery')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-24 w-24 rounded-md object-cover object-center sm:h-32 sm:w-32']) }}
                                        @else
                                            <div class="relative h-24 w-24 rounded-md bg-slate-100 sm:h-32 sm:w-32">
                                                <x-heroicon-o-camera class="h-full w-16 absolute inset-0 mx-auto text-slate-400 sm:w-24" />
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-4 flex flex-1 flex-col sm:ml-6">
                                        <div>
                                            <div class="flex justify-between">
                                                <h4 class="text-sm">
                                                    
                                                    {{ $item->product->name }}
                                                    
                                                </h4>
                                                <p class="ml-4 text-sm font-medium text-slate-900">
                                                    <x-money
                                                        :amount="$item->price"
                                                        :currency="config('app.currency')"
                                                    />
                                                </p>
                                            </div>
                                            @if($item->variant->variantAttributes->count())
                                                <ul class="mt-1 space-x-2 divide-x divide-slate-200 text-sm text-slate-500">
                                                    @foreach($item->variant->variantAttributes as $attribute)
                                                        <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>

                                        <div class="mt-4 flex flex-1 items-end justify-between">
                                            <div>
                                                <x-input-label
                                                    for="quantity"
                                                    class="sr-only"
                                                    :value="__('Quantidade')"
                                                />
                                                <x-input
                                                    wire:change="updateCartItemQuantity({{ $item->id }}, $event.target.value)"
                                                    type="number"
                                                    name="quantity"
                                                    value="{{ $item->quantity }}"
                                                    id="quantity"
                                                    max="{{ $item->category->quantity }}"
                                                    class="w-16 no-spinners text-center sm:text-sm"
                                                />
                                            </div>
                                            <div class="ml-4">
                                                <button
                                                    wire:click.prevent="removeCartItem({{ $item->id }})"
                                                    type="button"
                                                    class="btn bg-warning text-white"
                                                >
                                                    <span>{{ __('Remover') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                    <section
                        aria-labelledby="summary-heading"
                        class="mt-10"
                    >
                        <h2
                            id="summary-heading"
                            class="sr-only"
                        >
                            {{ __('Resumo do Pedido') }}
                        </h2>

                        <div>
                            <dl class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <dt class="text-base font-medium text-slate-900">
                                        {{ __('Subtotal') }}
                                    </dt>
                                    <dd class="ml-4 text-base font-medium text-slate-900">
                                        <x-money
                                            :amount="$cart->subtotal"
                                            :currency="config('app.currency')"
                                        />
                                    </dd>
                                </div>
                            </dl>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ __('Frete e impostos serão calculados na finalização da compra.') }}
                            </p>
                        </div>

                        <div class="mt-10">
                            <a
                                href="{{ route('guest.checkout') }}"
                                class="btn btn-primary btn-xl w-full"
                            >
                                {{ __('Finalizar') }}
                            </a>
                        </div>

                        <div class="mt-6 text-center text-sm">
                            <p>
                                {{ __('ou') }}
                                <a
                                    href="{{ route('guest.products.list', $prison) }}"
                                    class="btn btn-link"
                                >
                                    {{ __('Continuar comprando') }}
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </p>
                        </div>
                    </section>
                </div>
            @endunless
        </div>
    </div>
</div>
