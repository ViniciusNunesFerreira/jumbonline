<div>
    <x-slot:title>{{ __('Financeiro') }}</x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">{{ __('Financeiro') }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-2 sm:mt-0">
                <div class="flex rounded-xl bg-slate-100 p-1 dark:bg-white/5">
                    @foreach(['today' => 'Hoje', '7d' => '7 dias', 'month' => 'Este mês', 'last_month' => 'Mês passado'] as $key => $label)
                        <button
                            wire:click="applyPreset('{{ $key }}')"
                            type="button"
                            @class(['rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors', 'bg-white text-primary shadow-sm dark:bg-slate-800 dark:text-white' => $preset === $key, 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white' => $preset !== $key])
                        >
                            {{ __($label) }}
                        </button>
                    @endforeach
                </div>
                <div class="h-6 w-px bg-slate-200 dark:bg-white/10"></div>
                <a href="{{ route('employee.financial.abc-curve') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-chart-bar class="w-4 h-4 mr-1" />{{ __('Curva ABC') }}
                </a>
                <a href="{{ route('employee.financial.cash-reconciliation') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-banknotes class="w-4 h-4 mr-1" />{{ __('Conciliação de caixa') }}
                </a>
                <div class="h-6 w-px bg-slate-200 dark:bg-white/10"></div>
                <button wire:click="exportPdf" type="button" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-document-arrow-down class="w-4 h-4 mr-1" />PDF
                </button>
                <button wire:click="exportXls" type="button" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-table-cells class="w-4 h-4 mr-1" />XLS
                </button>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <x-card class="lg:col-span-1 bg-gradient-to-br from-primary-500 to-primary-700 ring-0">
                    <x-slot:content>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-white/70">{{ __('Receita líquida') }}</dt>
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                                <x-heroicon-o-banknotes class="h-5 w-5 text-accent-300" />
                            </span>
                        </div>
                        <dd class="mt-2 text-3xl font-extrabold text-white">
                            <x-money :amount="$metrics['net_revenue']" />
                        </dd>
                        <p class="mt-1 text-xs text-white/60">{{ $metrics['paid_orders_count'] }} {{ __('pedidos pagos no período') }}</p>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:content>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Margem bruta') }}</dt>
                        <dd class="mt-2 text-3xl font-extrabold text-primary dark:text-white">
                            {{ $metrics['margin']['margin_percent'] }}%
                        </dd>
                        <p class="mt-1 text-xs text-slate-400">
                            <x-money :amount="$metrics['margin']['profit']" /> {{ __('de lucro no período') }}
                        </p>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:content>
                        @php
                            $site = $metrics['by_channel']['site'];
                            $pdv = $metrics['by_channel']['pdv'];
                            $totalChannel = $site + $pdv;
                            $sitePercent = $totalChannel > 0 ? round(($site / $totalChannel) * 100) : 0;
                        @endphp
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-3">{{ __('Site vs. Balcão') }}</dt>
                        <div class="flex items-center gap-4">
                            <div
                                class="relative h-16 w-16 shrink-0 rounded-full"
                                style="background: conic-gradient(#F1598F 0% {{ $sitePercent }}%, #66648A {{ $sitePercent }}% 100%);"
                            >
                                <div class="absolute inset-1.5 flex items-center justify-center rounded-full bg-white dark:bg-slate-900">
                                    <span class="text-xs font-bold text-primary dark:text-white">{{ $sitePercent }}%</span>
                                </div>
                            </div>
                            <div class="space-y-1.5 text-sm">
                                <div class="flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-accent-500"></span>
                                    <span class="text-slate-500 dark:text-slate-400">{{ __('Site') }}</span>
                                    <span class="font-semibold text-primary dark:text-white"><x-money :amount="$site" /></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full" style="background-color: #66648A"></span>
                                    <span class="text-slate-500 dark:text-slate-400">{{ __('Balcão') }}</span>
                                    <span class="font-semibold text-primary dark:text-white"><x-money :amount="$pdv" /></span>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <x-card>
                    <x-slot:header>
                        <h3 class="font-semibold text-sm text-primary dark:text-slate-200">{{ __('Receita por método de pagamento') }}</h3>
                    </x-slot:header>
                    <x-slot:content>
                        @php $sortedMethods = collect($metrics['by_payment_method'])->sortByDesc('total')->values(); $maxMethod = $sortedMethods->max('total') ?: 1; @endphp
                        <div class="space-y-4">
                            @forelse($sortedMethods as $row)
                                <div>
                                    <div class="flex justify-between text-sm mb-1.5">
                                        <span class="font-medium text-slate-600 dark:text-slate-300">{{ $row['method'] }}</span>
                                        <span class="font-semibold text-primary dark:text-slate-200"><x-money :amount="$row['total']" /></span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 dark:bg-white/5">
                                        <div class="h-2 rounded-full bg-accent-500" style="width: {{ ($row['total'] / $maxMethod) * 100 }}%"></div>
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
                            <h3 class="font-semibold text-sm text-primary dark:text-slate-200">{{ __('Top produtos (curva ABC)') }}</h3>
                            <a href="{{ route('employee.financial.abc-curve') }}" class="btn btn-link btn-xs">{{ __('Ver tudo') }}</a>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <ul class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($metrics['abc_top'] as $product)
                                <li class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <x-badge :type="$product['class'] === 'A' ? 'success' : ($product['class'] === 'B' ? 'warning' : 'default')" size="xs">
                                            {{ $product['class'] }}
                                        </x-badge>
                                        <span class="truncate text-sm text-slate-600 dark:text-slate-300">{{ $product['name'] }}</span>
                                    </div>
                                    <span class="shrink-0 text-sm font-semibold text-primary dark:text-slate-200"><x-money :amount="$product['revenue']" /></span>
                                </li>
                            @empty
                                <li class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400">{{ __('Nenhuma venda no período.') }}</li>
                            @endforelse
                        </ul>
                    </x-slot:content>
                </x-card>
            </div>

            <x-card>
                <x-slot:header>
                    <h3 class="font-semibold text-sm text-primary dark:text-slate-200">{{ __('Unidades prisionais com maior volume') }}</h3>
                </x-slot:header>
                <x-slot:content>
                    @php $maxUnit = collect($metrics['prison_ranking_top'])->max('revenue') ?: 1; @endphp
                    <div class="space-y-4">
                        @forelse($metrics['prison_ranking_top'] as $unit)
                            <div>
                                <div class="flex items-baseline justify-between text-sm mb-1.5">
                                    <span class="font-medium text-slate-600 dark:text-slate-300">{{ $unit['name'] }}</span>
                                    <div class="text-right">
                                        <span class="font-semibold text-primary dark:text-slate-200"><x-money :amount="$unit['revenue']" /></span>
                                        <span class="ml-1 text-xs text-slate-400">({{ $unit['orders_count'] }} {{ __('pedidos') }})</span>
                                    </div>
                                </div>
                                <div class="h-1.5 rounded-full bg-slate-100 dark:bg-white/5">
                                    <div class="h-1.5 rounded-full bg-primary-400" style="width: {{ ($unit['revenue'] / $maxUnit) * 100 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Nenhuma venda no período.') }}</p>
                        @endforelse
                    </div>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</div>