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
            @if($stalledCount > 0 && !$filterStalledOnly)
                <div class="mb-4 flex flex-col gap-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4 sm:flex-row sm:items-center sm:justify-between dark:border-yellow-900 dark:bg-yellow-900/20">
                    <div class="flex items-start gap-3">
                        <x-heroicon-m-exclamation-triangle class="mt-0.5 h-5 w-5 flex-shrink-0 text-yellow-500 dark:text-yellow-400" />
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            <span class="font-semibold">
                                {{ trans_choice(':count pedido pago está|:count pedidos pagos estão', $stalledCount) }}
                            </span>
                            {{ trans_choice('parado há mais de :days dia sem envio criado.|parados há mais de :days dias sem envio criado.', $stalledThresholdDays, ['days' => $stalledThresholdDays]) }}
                        </p>
                    </div>
                    <button
                        wire:click="$set('filterStalledOnly', true)"
                        type="button"
                        class="btn btn-default btn-xs !rounded-xl flex-shrink-0 !bg-white dark:!bg-slate-800"
                    >
                        {{ __('Ver pedidos parados') }}
                    </button>
                </div>
            @endif

            @if(!$orders->count() && !$search && !$filterStalledOnly)
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
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <div
                                x-data="{ search: @entangle('search'), focused: false }"
                                class="relative w-full max-w-md"
                            >
                                <div class="relative text-slate-400 focus-within:text-primary dark:focus-within:text-slate-200">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span wire:loading.remove wire:target="search">
                                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                                        </span>
                                        <span wire:loading wire:target="search">
                                            <x-loading-spinner class="h-5 w-5 text-accent-500" />
                                        </span>
                                    </div>
                                    <x-input
                                        wire:model.debounce.500ms="search"
                                        type="text"
                                        autocomplete="off"
                                        spellcheck="false"
                                        x-on:focus="focused = true"
                                        x-on:blur="focused = false"
                                        x-on:keydown.escape="search = ''"
                                        class="placeholder-slate-400 w-full rounded-xl pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                                        ::class="{ 'pr-10' : search }"
                                        placeholder="{{ __('Nº do pedido, cliente, telefone ou rastreio') }}"
                                        aria-label="{{ __('Buscar pedidos') }}"
                                    />
                                    <button
                                        x-show="search.length"
                                        x-on:click="search = ''"
                                        type="button"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3"
                                        aria-label="{{ __('Limpar busca') }}"
                                    >
                                        <x-heroicon-s-x-circle class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-400" />
                                    </button>
                                </div>

                                <div
                                    x-show="focused && !search.length"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                    x-cloak
                                    class="absolute left-0 right-0 top-full z-20 mt-2 rounded-xl border border-slate-100 bg-white p-3 shadow-lg ring-1 ring-black/5 dark:border-white/10 dark:bg-slate-800 dark:ring-white/10"
                                >
                                    <p class="px-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        {{ __('Você pode buscar por') }}
                                    </p>
                                    <ul class="mt-2 space-y-1">
                                        @php
                                            $searchHints = [
                                                ['heroicon-m-hashtag', '#1234', __('Número exato do pedido')],
                                                ['heroicon-m-user', 'Maria Silva', __('Nome ou e-mail do cliente')],
                                                ['heroicon-m-phone', '(15) 99999-8888', __('Telefone completo ou parcial')],
                                                ['heroicon-m-hashtag', '8888', __('Final do telefone, com 4 dígitos ou mais')],
                                                ['heroicon-m-truck', 'AB123456789BR', __('Código de rastreio dos Correios')],
                                            ];
                                        @endphp
                                        @foreach($searchHints as [$hintIcon, $hintExample, $hintLabel])
                                            <li class="flex items-center gap-3 rounded-lg px-1 py-1.5">
                                                <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-accent-50 dark:bg-accent-500/10">
                                                    <x-dynamic-component :component="$hintIcon" class="h-4 w-4 text-accent-500" />
                                                </span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $hintLabel }}</p>
                                                </div>
                                                <code class="flex-shrink-0 rounded-md bg-slate-50 px-1.5 py-0.5 font-mono text-[11px] text-slate-500 dark:bg-white/5 dark:text-slate-400">{{ $hintExample }}</code>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            @if($searchInterpretation || $filterStalledOnly)
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    @if($searchInterpretation)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-accent-50 px-2.5 py-1 font-medium text-accent-700 ring-1 ring-inset ring-accent-600/20 dark:bg-accent-400/10 dark:text-accent-400 dark:ring-accent-400/20">
                                            <x-dynamic-component :component="$searchInterpretation['icon']" class="h-3.5 w-3.5" />
                                            {{ __('Buscando por') }}: {{ $searchInterpretation['label'] }}
                                        </span>
                                    @endif
                                    @if($filterStalledOnly)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-2.5 py-1 font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-400/10 dark:text-yellow-300 dark:ring-yellow-400/20">
                                            <x-heroicon-m-exclamation-triangle class="h-3.5 w-3.5" />
                                            {{ __('Somente pedidos parados') }}
                                            <button
                                                wire:click="$set('filterStalledOnly', false)"
                                                type="button"
                                                class="-mr-0.5 ml-0.5 flex items-center hover:text-yellow-900 dark:hover:text-yellow-100"
                                                aria-label="{{ __('Remover filtro de pedidos parados') }}"
                                            >
                                                <x-heroicon-m-x-mark class="h-3.5 w-3.5" />
                                            </button>
                                        </span>
                                    @endif
                                    <span class="text-slate-500 tabular-nums dark:text-slate-400">
                                        {{ trans_choice(':count pedido encontrado|:count pedidos encontrados', $orders->total()) }}
                                    </span>
                                </div>
                            @endif
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
                                                @php($meta = $rowMeta[$order->id])
                                                <tr
                                                    wire:key="order-row-{{ $order->id }}"
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
                                                        @if($meta['reasons'] || $meta['stalled'])
                                                            <div class="mt-1 flex flex-wrap gap-1">
                                                                @foreach($meta['reasons'] as $reason)
                                                                    <x-badge type="primary" size="xs" class="!text-[10px] !font-medium">
                                                                        {{ $reason }}
                                                                    </x-badge>
                                                                @endforeach
                                                                @if($meta['stalled'])
                                                                    <x-badge
                                                                        type="warning"
                                                                        size="xs"
                                                                        class="!text-[10px] !font-medium"
                                                                        data-tippy-content="{{ __('Pago em :date', ['date' => $meta['stalled']['paid_at']->format('d/m/Y H:i')]) }}"
                                                                    >
                                                                        {{ trans_choice('Parado há :count dia|Parado há :count dias', $meta['stalled']['days']) }}
                                                                    </x-badge>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        @if($order->customer)
                                                            <a href="{{ route('employee.customers.detail', $order->customer) }}" class="text-slate-600 hover:text-accent-600 dark:text-slate-300 dark:hover:text-accent-400">
                                                                {{ $order->customer->name }}
                                                            </a>
                                                            @if($meta['phone'])
                                                                <p class="mt-0.5 flex items-center gap-1 text-xs text-slate-400 tabular-nums dark:text-slate-500">
                                                                    <x-heroicon-m-phone class="h-3 w-3" />
                                                                    {{ $meta['phone'] }}
                                                                </p>
                                                            @endif
                                                        @else
                                                            <span class="text-slate-400">{{ __('Sem cliente') }}</span>
                                                            @if($order->customer_email)
                                                                <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">{{ $order->customer_email }}</p>
                                                            @endif
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
                                                        @if($meta['tracking'])
                                                            <div class="mt-1 flex items-center gap-1.5">
                                                                <button
                                                                    type="button"
                                                                    x-on:click="$clipboard(@js($meta['tracking'])).then(() => $dispatch('notify', '{{ __('Código de rastreio copiado') }}'))"
                                                                    data-tippy-content="{{ __('Copiar rastreio') }}"
                                                                    class="group inline-flex items-center gap-1 font-mono text-[11px] text-slate-500 hover:text-accent-600 dark:text-slate-400 dark:hover:text-accent-400"
                                                                >
                                                                    {{ $meta['tracking'] }}
                                                                    <x-heroicon-m-clipboard class="h-3.5 w-3.5 transition-opacity sm:opacity-0 sm:group-hover:opacity-100" />
                                                                </button>
                                                                @if($meta['extra_trackings'] > 0)
                                                                    <span class="text-[11px] text-slate-400 tabular-nums">+{{ $meta['extra_trackings'] }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
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
                                                                @if($filterStalledOnly && !$searchInterpretation)
                                                                    <x-heroicon-o-check-circle class="h-6 w-6 text-green-500" />
                                                                @else
                                                                    <x-heroicon-o-magnifying-glass class="h-6 w-6 text-slate-400" />
                                                                @endif
                                                            </span>
                                                            <h3 class="mt-3 text-sm font-semibold text-primary dark:text-slate-200">
                                                                @if($filterStalledOnly && !$searchInterpretation)
                                                                    {{ __('Nenhum pedido parado') }}
                                                                @else
                                                                    {{ __('Nenhum pedido encontrado') }}
                                                                @endif
                                                            </h3>

                                                            @if($searchInterpretation && $filterStalledOnly)
                                                                <p class="mt-1 whitespace-normal text-sm text-slate-500 dark:text-slate-400">
                                                                    {{ __('Nenhum resultado para') }}
                                                                    <span class="font-medium text-slate-700 dark:text-slate-300">“{{ $search }}”</span>
                                                                    {{ __('entre os pedidos parados.') }}
                                                                </p>
                                                                <div class="mt-4 flex flex-wrap justify-center gap-2">
                                                                    <button wire:click="clearSearch" type="button" class="btn btn-default btn-xs !rounded-xl">
                                                                        {{ __('Limpar busca') }}
                                                                    </button>
                                                                    <button wire:click="$set('filterStalledOnly', false)" type="button" class="btn btn-default btn-xs !rounded-xl">
                                                                        {{ __('Ver todos os pedidos') }}
                                                                    </button>
                                                                </div>
                                                            @elseif($searchInterpretation)
                                                                <p class="mt-1 whitespace-normal text-sm text-slate-500 dark:text-slate-400">
                                                                    {{ __('Nenhum resultado para') }}
                                                                    <span class="font-medium text-slate-700 dark:text-slate-300">“{{ $search }}”</span>
                                                                    {{ __('buscando por') }} {{ \Illuminate\Support\Str::lower($searchInterpretation['label']) }}.
                                                                </p>
                                                                <p class="mt-1 whitespace-normal text-xs text-slate-400 dark:text-slate-500">
                                                                    {{ __('Dica: para telefone, digite ao menos 4 dígitos; para número exato do pedido, use # antes (ex.: #1234).') }}
                                                                </p>
                                                                <div class="mt-4">
                                                                    <button wire:click="clearSearch" type="button" class="btn btn-default btn-xs !rounded-xl">
                                                                        {{ __('Limpar busca') }}
                                                                    </button>
                                                                </div>
                                                            @elseif($filterStalledOnly)
                                                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                                    {{ __('Nenhum pedido pago está parado no momento. Bom sinal!') }}
                                                                </p>
                                                                <div class="mt-4">
                                                                    <button wire:click="$set('filterStalledOnly', false)" type="button" class="btn btn-default btn-xs !rounded-xl">
                                                                        {{ __('Ver todos os pedidos') }}
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                                    {{ __('Tente alterar os filtros ou o termo de pesquisa') }}
                                                                </p>
                                                            @endif
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