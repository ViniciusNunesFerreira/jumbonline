<div>
    <x-modal-dialog wire:model="isShown">
        <x-slot:title>
           Pacote Jumbo
        </x-slot:title>
        <x-slot:content>
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
                        <button
                            x-on:click="show = false"
                            type="button"
                            class="btn btn-primary"
                        >
                            {{ __('Continuar comprando') }}
                        </button>
                    </div>
                </div>
            @else
                <section aria-labelledby="cart-heading">
                    <h3
                        id="cart-heading"
                        class="sr-only"
                    >
                        {{ __('Itens em seu carrinho de compras') }}
                    </h3>

                    <ul
                        role="list"
                        class="divide-y divide-slate-200 border-b border-slate-200"
                    >
                        @foreach($cartItems as $item)
                            <li class="flex py-6">
                                <div class="flex-shrink-0 border border-slate-200 rounded-md">
                                    <img
                                        src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image') : $item->product->getFirstMediaUrl('gallery', 'thumb_large') }}"
                                        alt="{{ $item->product->name }}"
                                        class="h-24 w-24 rounded-md object-cover object-center sm:h-32 sm:w-32"
                                    >
                                </div>

                                <div class="ml-4 flex flex-1 flex-col">
                                    <div>
                                        <div class="flex justify-between">
                                            <h4 class="text-sm line-clamp-2">
                                                <a
                                                    href="{{ route('guest.products.detail', $item->product) }}"
                                                    class="font-medium text-slate-700 hover:text-slate-800"
                                                >
                                                    {{ $item->product->name }}
                                                </a>
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
                                    <div class="flex flex-1 items-end justify-between text-sm">
                                        <p class="text-slate-500">{{ __('Quantidade: :count', ['count' => $item->quantity]) }}</p>

                                        <div class="flex">
                                            <button
                                                wire:click="removeCartItem('{{ $item->id }}')"
                                                type="button"
                                                class="btn btn-link"
                                            >
                                                {{ __('Remover') }}
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
                        {{ __('Resumo do pedido') }}
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

                    <div class="mt-10 space-y-3">
                        <a
                            href="{{ route('guest.cart') }}"
                            class="btn btn-default btn-lg w-full"
                        >
                            {{ __('Ver Carrinho') }}
                        </a>
                        <a
                            href="{{ route('guest.checkout') }}"
                            class="btn btn-primary btn-lg w-full"
                        >
                            {{ __('Prosseguir para Pagamento') }}
                        </a>
                    </div>

                    <div class="mt-6 text-center text-sm">
                        <p>
                            {{ __('ou') }}
                            <button
                                x-on:click="show = false"
                                type="button"
                                class="btn btn-link"
                            >
                                {{ __('Continuar Comprando') }}
                                <span aria-hidden="true"> &rarr;</span>
                            </button>
                        </p>
                    </div>
                </section>
            @endunless
        </x-slot:content>
    </x-modal-dialog>
</div>
