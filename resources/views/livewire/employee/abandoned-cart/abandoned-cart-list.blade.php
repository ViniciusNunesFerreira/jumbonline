<div x-data="{ confirmingCartId: null }">
    <x-slot:title>
        {{ __('Carrinhos Abandonados') }}
    </x-slot:title>

    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">
                {{ __('Carrinhos Abandonados') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Clientes com itens parados no carrinho há mais de 2 horas. Contato manual, sem envio automático.') }}
            </p>
        </div>
    </div>

    <div class="p-4 mx-auto max-w-5xl sm:px-6 lg:px-8">

        <div
            x-data="{ search: @entangle('search') }"
            class="relative max-w-sm text-slate-400 focus-within:text-slate-600 dark:focus-within:text-slate-200"
        >
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-heroicon-o-magnifying-glass class="h-5 w-5" />
            </div>
            <x-input
                wire:model.debounce.500ms="search"
                type="text"
                class="placeholder-slate-500 w-full pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                ::class="{ 'pr-10' : search }"
                placeholder="{{ __('Buscar por nome ou e-mail...') }}"
            />
            <button
                x-show="search.length"
                x-on:click="search = ''"
                type="button"
                class="absolute inset-y-0 right-0 flex items-center pr-3"
            >
                <x-heroicon-o-x-mark class="h-5 w-5 text-slate-400 hover:text-slate-500" />
            </button>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($carts as $cart)
                <div wire:key="cart-{{ $cart->id }}" class="overflow-hidden rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">

                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-900/50 sm:px-6">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $cart->customer->name }}</p>
                            <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $cart->customer->email }}</p>
                        </div>
                        <span class="inline-flex flex-shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                            <x-heroicon-s-clock class="h-3.5 w-3.5" />
                            {{ __('Parado há :time', ['time' => $cart->updated_at->diffForHumans(null, true)]) }}
                        </span>
                    </div>

                    <ul role="list" class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        @foreach($cart->items as $item)
                            <li wire:key="cart-item-{{ $item->id }}" class="flex items-center justify-between gap-3 px-4 py-2.5 sm:px-6">
                                <span class="truncate text-sm text-slate-700 dark:text-slate-300">{{ $item->product->name }}</span>
                                <span class="flex-shrink-0 text-xs font-medium text-slate-400">×{{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="flex flex-col gap-2 border-t border-slate-200 px-4 py-3 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-end sm:px-6">
                        @if($cart->customer->phone)
                            
                            <a    href="https://wa.me/{{ str_replace('+', '', $cart->customer->phone) }}?text={{ urlencode('Olá ' . explode(' ', $cart->customer->name)[0] . ', tudo bem? Vi que você estava montando um jumbo aqui na Jumbonline e queria saber se posso te ajudar a finalizar :)') }}"
                                target="_blank"
                                class="flex items-center justify-center gap-1.5 rounded-md bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50"
                            >
                                <x-heroicon-s-chat-bubble-left-right class="h-3.5 w-3.5" />
                                {{ __('Chamar no WhatsApp') }}
                            </a>
                        @endif
                        <button
                            x-on:click="confirmingCartId = {{ $cart->id }}"
                            wire:loading.attr="disabled"
                            wire:target="markAsContacted"
                            type="button"
                            class="flex items-center justify-center gap-1.5 rounded-md border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800"
                        >
                            <x-heroicon-s-check class="h-3.5 w-3.5" />
                            {{ __('Marcar como contatado') }}
                        </button>
                    </div>
                </div>
            @empty
                <x-card>
                    <x-slot:content>
                        <div class="mx-auto max-w-lg text-center">
                            <x-heroicon-o-shopping-cart class="mx-auto h-12 w-12 text-slate-400" />
                            <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">
                                {{ __('Nenhum carrinho abandonado no momento') }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Assim que um cliente deixar itens parados no carrinho por mais de 2 horas, ele aparece aqui.') }}
                            </p>
                        </div>
                    </x-slot:content>
                </x-card>
            @endforelse
        </div>

        @if($carts->hasPages())
            <div class="mt-6">{{ $carts->links() }}</div>
        @endif
    </div>

    <!-- Modal de confirmação -->
    <div
        x-show="confirmingCartId !== null"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
    >
        <div class="w-full max-w-sm rounded-lg bg-white p-6 shadow-xl dark:bg-slate-800">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                {{ __('Marcar como contatado?') }}
            </h3>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Esse carrinho sai da lista de pendentes assim que confirmar.') }}
            </p>
            <div class="mt-5 flex justify-end gap-2">
                <button
                    x-on:click="confirmingCartId = null"
                    type="button"
                    class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700"
                >
                    {{ __('Cancelar') }}
                </button>
                <button
                    x-on:click="$wire.markAsContacted(confirmingCartId); confirmingCartId = null"
                    type="button"
                    class="rounded-md bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700"
                >
                    {{ __('Confirmar') }}
                </button>
            </div>
        </div>
    </div>
</div>