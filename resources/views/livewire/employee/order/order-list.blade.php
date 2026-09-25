<div>
    <x-slot:title>
        {{ __('Pedidos') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Pedidos') }}
                </h1>
            </div>
        </div>

        <div class="mt-6">
            @if(!$orders->count() && !$search)
                <x-card>
                    <x-slot:content>
                        <div class="max-w-lg mx-auto text-center py-6">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                <x-heroicon-o-inbox-arrow-down class="h-7 w-7 text-accent-500" />
                            </span>

                            <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Seus pedidos serão exibidos aqui') }}
                            </h3>

                            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Aqui que você atenderá pedidos, receberá pagamentos e acompanhará o andamento dos pedidos.') }}
                            </p>
                        </div>
                    </x-slot:content>
                </x-card>
            @else
                <x-card class="overflow-hidden">
                    <x-slot:header>
                        <div
                            x-data="{ search: @entangle('search')}"
                            class="relative max-w-sm text-slate-400 focus-within:text-primary dark:focus-within:text-slate-200"
                        >
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                            </div>
                            <x-input
                                wire:model.debounce.500ms="search"
                                type="text"
                                class="placeholder-slate-400 w-full rounded-xl pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                                ::class="{ 'pr-10' : search }"
                                placeholder="{{ __('Filtrar pedidos por número') }}"
                            />
                            <button
                                x-show="search.length"
                                x-on:click="search = ''"
                                type="button"
                                class="absolute inset-y-0 right-0 flex items-center pr-3"
                            >
                                <x-heroicon-s-x-circle class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-400" />
                            </button>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="relative overflow-hidden">
                                    <div
                                        wire:loading.delay
                                        class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[1px] dark:bg-slate-900/60"
                                    >
                                        <div
                                            wire:loading.flex
                                            class="h-full w-screen items-center justify-center sm:w-auto"
                                        >
                                            <div class="m-auto flex items-center space-x-2">
                                                <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Carregando pedidos...') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                                        <thead>
                                            <tr class="border-b border-slate-100 dark:border-white/5">
                                                <th scope="col" class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                    <x-input
                                                        wire:model="selectPage"
                                                        type="checkbox"
                                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                    />
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('ID') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Cliente') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Pagamento') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Envio') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Itens') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Total') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Data') }}
                                                </th>
                                                <th scope="col" class="pl-3 pr-4 py-3.5 sm:pr-6"><span class="sr-only">{{ __('Ver pedido') }}</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @forelse($orders as $order)
                                                <tr
                                                    wire:loading.class.delay="opacity-50"
                                                    class="relative transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]"
                                                >
                                                    <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                        @if(in_array($order->id, $selected))
                                                            <div class="absolute inset-y-0 left-0 w-0.5 bg-accent-500"></div>
                                                        @endif
                                                        <x-input
                                                            wire:model="selected"
                                                            wire:key="checkbox-{{ $order->id }}"
                                                            type="checkbox"
                                                            value="{{ $order->id }}"
                                                            class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                        />
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm whitespace-nowrap tabular-nums">
                                                        <a href="{{ route('employee.orders.detail', $order) }}" class="font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400">
                                                            #{{ $order->id }}
                                                        </a>
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        @if($order->customer)
                                                            <a href="{{ route('employee.customers.detail', $order->customer) }}" class="text-slate-600 hover:text-accent-600 dark:text-slate-300 dark:hover:text-accent-400">
                                                                {{ $order->customer->name }}
                                                            </a>
                                                        @else
                                                            <span class="text-slate-400">{{ __('Sem cliente') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium" style="background-color: {{ $order->payment_status->color() }}1A; color: {{ $order->payment_status->color() }};">
                                                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $order->payment_status->color() }}"></span>
                                                            {{ $order->payment_status->label() }}
                                                        </span>
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium" style="background-color: {{ $order->shipping_status->color() }}1A; color: {{ $order->shipping_status->color() }};">
                                                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $order->shipping_status->color() }}"></span>
                                                            {{ $order->shipping_status->label() }}
                                                        </span>
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap tabular-nums dark:text-slate-400">
                                                        {{ trans_choice(':count item|:count itens', $order->order_items_sum_quantity) }}
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-right font-semibold text-primary whitespace-nowrap tabular-nums dark:text-slate-200">
                                                        <x-money :amount="$order->total" />
                                                    </td>
                                                    <td class="px-3 py-4 text-right text-sm text-slate-500 whitespace-nowrap tabular-nums dark:text-slate-400">
                                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td class="relative pl-3 pr-4 py-4 text-right whitespace-nowrap sm:pr-6">
                                                        <a href="{{ route('employee.orders.detail', $order) }}" class="inline-flex text-slate-400 hover:text-accent-500">
                                                            <x-heroicon-o-eye class="h-5 w-5" />
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="px-3 py-12 text-sm text-center whitespace-nowrap" colspan="9">
                                                        <div class="max-w-lg mx-auto text-center">
                                                            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 dark:bg-white/5">
                                                                <x-heroicon-o-magnifying-glass class="h-6 w-6 text-slate-400" />
                                                            </span>
                                                            <h3 class="mt-3 text-sm font-semibold text-primary dark:text-slate-200">
                                                                {{ __('Nenhum pedido encontrado') }}
                                                            </h3>
                                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                                {{ __('Tente alterar os filtros ou o termo de pesquisa') }}
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>