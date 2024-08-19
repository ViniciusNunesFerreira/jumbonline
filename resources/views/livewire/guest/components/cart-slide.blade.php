<div>
    <x-slide-over wire:model="isShown">
        <x-slot:title>
            {{ __('Shopping cart') }}
        </x-slot:title>
        <x-slot:content>
            <div class="flow-root">
                <ul
                    role="list"
                    class="-my-6 divide-y divide-gray-200"
                >
                    @forelse($cartItems as $item)
                        <li
                            wire:key="cart-item-id-{{ $item->id }}"
                            class="flex py-6"
                        >
                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                <img
                                    src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image') : $item->product->getFirstMediaUrl('gallery', 'thumb_large') }}"
                                    alt="{{ $item->product->name }}"
                                    class="h-full w-full object-cover object-center"
                                    loading="lazy"
                                >
                            </div>

                            <div class="ml-4 flex flex-1 flex-col">
                                <div>
                                    <div class="flex justify-between">
                                        <h3 class="text-sm line-clamp-2">
                                            <a
                                                href="{{ route('guest.products.detail', $item->product) }}"
                                                class="font-medium text-slate-700 hover:text-slate-800"
                                            >
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
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
                                    <p class="text-slate-500">{{ __('Quantity: :count', ['count' => $item->quantity]) }}</p>

                                    <div class="flex">
                                        <button
                                            wire:click="removeCartItem('{{ $item->id }}')"
                                            type="button"
                                            class="btn btn-link"
                                        >
                                            {{ __('Remove') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-6 flex flex-col items-center justify-center">
                            <x-heroicon-o-shopping-cart class="mx-auto h-24 w-24 text-slate-400" />

                            <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">
                                {{ __('Your shopping cart is currently empty') }}
                            </h3>

                            <div class="mt-6">
                                <button
                                    x-on:click="show = false"
                                    type="button"
                                    class="btn btn-primary"
                                >
                                    {{ __('Continue shopping') }}
                                </button>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </x-slot:content>
        @if(count($cartItems))
            <x-slot:footer>
                <div class="flex justify-between text-base font-medium text-gray-900">
                    <p>
                        {{ __('Subtotal') }}
                    </p>
                    <p>
                        <x-money
                            :amount="$cart->subtotal ?? 0"
                            :currency="config('app.currency')"
                        />
                    </p>
                </div>
                <p class="mt-0.5 text-sm text-gray-500">
                    {{ __('Shipping and taxes will be calculated at checkout.') }}
                </p>
                <div class="mt-6">
                    <a
                        href="{{ route('guest.checkout') }}"
                        class="btn btn-xl btn-primary w-full"
                    >
                        {{ __('Checkout') }}
                    </a>
                </div>
                <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                    <p>
                        {{ __('or') }}
                        <button
                            x-on:click="show = false"
                            type="button"
                            class="btn btn-link"
                        >
                            {{ __('Continue Shopping') }}
                            <span aria-hidden="true"> &rarr;</span>
                        </button>
                    </p>
                </div>
            </x-slot:footer>
        @endif
    </x-slide-over>
</div>
