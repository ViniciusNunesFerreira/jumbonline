<div>
    <x-slot:title>{{ __('Financeiro') }}</x-slot:title>

    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">{{ __('Financeiro') }}</h1>
        <div class="mt-4 flex flex-wrap items-center gap-2 sm:mt-0">
            @foreach(['today' => 'Hoje', '7d' => '7 dias', 'month' => 'Este mês', 'last_month' => 'Mês passado'] as $key => $label)
                <button
                    wire:click="applyPreset('{{ $key }}')"
                    type="button"
                    class="btn btn-xs {{ $preset === $key ? 'btn-primary' : 'btn-default' }}"
                >
                    {{ __($label) }}
                </button>
            @endforeach
            <a href="{{ route('employee.financial.abc-curve') }}" class="btn btn-default btn-xs">{{ __('Curva ABC') }}</a>
            <a href="{{ route('employee.financial.cash-reconciliation') }}" class="btn btn-default btn-xs">{{ __('Conciliação de caixa') }}</a>
            <button wire:click="exportPdf" type="button" class="btn btn-default btn-xs">
                <x-heroicon-m-document-arrow-down class="w-4 h-4 mr-1" />PDF
            </button>
            <button wire:click="exportXls" type="button" class="btn btn-default btn-xs">
                <x-heroicon-m-table-cells class="w-4 h-4 mr-1" />XLS
            </button>
        </div>
    </div>

    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <x-card>
                <x-slot:content>
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Receita líquida') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900 dark:text-slate-200">
                        <x-money :amount="$metrics['net_revenue']" />
                    </dd>
                </x-slot:content>
            </x-card>
            <x-card>
                <x-slot:content>
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Pedidos pagos') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900 dark:text-slate-200">
                        {{ $metrics['paid_orders_count'] }}
                    </dd>
                </x-slot:content>
            </x-card>
            <x-card>
                <x-slot:content>
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Margem bruta') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900 dark:text-slate-200">
                        {{ $metrics['margin']['margin_percent'] }}%
                    </dd>
                    <p class="text-xs text-slate-400 mt-0.5">
                        <x-money :amount="$metrics['margin']['profit']" /> {{ __('de lucro no período') }}
                    </p>
                </x-slot:content>
            </x-card>
            <x-card>
                <x-slot:content>
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Site vs. Balcão') }}</dt>
                    <dd class="mt-1 flex items-baseline gap-2 text-lg font-semibold text-slate-900 dark:text-slate-200">
                        <span><x-money :amount="$metrics['by_channel']['site']" /></span>
                        <span class="text-slate-300">/</span>
                        <span><x-money :amount="$metrics['by_channel']['pdv']" /></span>
                    </dd>
                </x-slot:content>
            </x-card>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <x-card>
                <x-slot:header>
                    <h3 class="font-display font-medium text-base text-slate-900 dark:text-slate-200">{{ __('Receita por método de pagamento') }}</h3>
                </x-slot:header>
                <x-slot:content>
                    @php $maxMethod = collect($metrics['by_payment_method'])->max('total') ?: 1; @endphp
                    <div class="space-y-3">
                        @forelse($metrics['by_payment_method'] as $row)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-slate-700 dark:text-slate-300">{{ $row['method'] }}</span>
                                    <span class="text-slate-500 dark:text-slate-400"><x-money :amount="$row['total']" /></span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="h-2 rounded-full bg-sky-500" style="width: {{ ($row['total'] / $maxMethod) * 100 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Nenhum pagamento no período.') }}</p>
                        @endforelse
                    </div>
                </x-slot:content>
            </x-card>

            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <h3 class="font-display font-medium text-base text-slate-900 dark:text-slate-200">{{ __('Top produtos (curva ABC)') }}</h3>
                        
                        <a href="#" class="btn btn-link btn-xs">{{ __('Ver tudo') }} route('employee.financial.abc-curve')  </a>
                    </div>
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <ul class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($metrics['abc_top'] as $product)
                            <li class="flex items-center justify-between px-4 py-3 sm:px-6">
                                <div class="flex items-center gap-2">
                                    <x-badge :type="$product['class'] === 'A' ? 'success' : ($product['class'] === 'B' ? 'warning' : 'default')" size="xs">
                                        {{ $product['class'] }}
                                    </x-badge>
                                    <span class="text-sm text-slate-700 dark:text-slate-300">{{ $product['name'] }}</span>
                                </div>
                                <span class="text-sm text-slate-500 dark:text-slate-400"><x-money :amount="$product['revenue']" /></span>
                            </li>
                        @empty
                            <li class="px-4 py-3 sm:px-6 text-sm text-slate-500 dark:text-slate-400">{{ __('Nenhuma venda no período.') }}</li>
                        @endforelse
                    </ul>
                </x-slot:content>
            </x-card>
        </div>

        <x-card>
            <x-slot:header>
                <h3 class="font-display font-medium text-base text-slate-900 dark:text-slate-200">{{ __('Unidades prisionais com maior volume') }}</h3>
            </x-slot:header>
            <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                <ul class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($metrics['prison_ranking_top'] as $unit)
                        <li class="flex items-center justify-between px-4 py-3 sm:px-6">
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $unit['name'] }}</span>
                            <div class="text-right">
                                <span class="text-sm text-slate-900 dark:text-slate-200 font-medium"><x-money :amount="$unit['revenue']" /></span>
                                <span class="block text-xs text-slate-400">{{ $unit['orders_count'] }} {{ __('pedidos') }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-3 sm:px-6 text-sm text-slate-500 dark:text-slate-400">{{ __('Nenhuma venda no período.') }}</li>
                    @endforelse
                </ul>
            </x-slot:content>
        </x-card>
    </div>
</div>