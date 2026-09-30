<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Dashboard') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Page title & actions -->
        <div class="py-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-medium leading-6 text-slate-900 sm:truncate dark:text-slate-100">
                    {{ __('Dashboard') }}
                </h1>
            </div>
            <div class="mt-4 flex sm:mt-0 sm:ml-4">
                <x-dropdown width="full">
                    <x-slot name="trigger">
                        <button
                            type="button"
                            class="btn btn-primary"
                        >
                            {{ __('Last :count days', ['count' => $periods]) }}
                            <x-heroicon-s-chevron-down class="ml-2 -mr-1 h-5 w-5" />
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link
                            role="button"
                            wire:click="$set('periods', 7)"
                        >
                            {{ __('Last :count days', ['count' => 7]) }}
                        </x-dropdown-link>
                        <x-dropdown-link
                            role="button"
                            wire:click="$set('periods', 10)"
                        >
                            {{ __('Last :count days', ['count' => 10]) }}
                        </x-dropdown-link>
                        <x-dropdown-link
                            role="button"
                            wire:click="$set('periods', 30)"
                        >
                            {{ __('Last :count days', ['count' => 30]) }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

        

        <!-- Painel operacional ("hoje") -->
        <div class="px-0 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <a
                    href="{{ route('employee.orders.list', ['filterStalledOnly' => true]) }}"
                    class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 transition-colors hover:border-accent-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-accent-500/40"
                >
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-yellow-50 dark:bg-yellow-400/10">
                        <x-heroicon-o-clock class="h-5 w-5 text-yellow-500" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-primary dark:text-white">{{ $this->stalledOrdersCount }}</p>
                        <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ __('Pedidos parados') }}</p>
                    </div>
                </a>

                <a
                    href="#low-stock-alert"
                    class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 transition-colors hover:border-accent-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-accent-500/40"
                >
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-orange-50 dark:bg-orange-400/10">
                        <x-heroicon-o-archive-box-x-mark class="h-5 w-5 text-orange-500" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-primary dark:text-white">{{ $this->lowStockCount }}</p>
                        <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ __('Estoque baixo') }}</p>
                    </div>
                </a>

                <a
                    href="{{ route('employee.abandoned-carts.list') }}"
                    class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 transition-colors hover:border-accent-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-accent-500/40"
                >
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                        <x-heroicon-o-shopping-cart class="h-5 w-5 text-accent-500" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-primary dark:text-white">{{ $this->abandonedCartsTodayCount }}</p>
                        <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ __('Carrinhos abandonados hoje') }}</p>
                    </div>
                </a>

                @can('admin')
                    <a
                        href="{{ route('employee.expenses.list', ['filterStatus' => 'pending', 'filterDueFrom' => today()->toDateString(), 'filterDueTo' => today()->addDays(7)->toDateString()]) }}"
                        class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 transition-colors hover:border-accent-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-accent-500/40"
                    >
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-400/10">
                            <x-heroicon-o-calendar-days class="h-5 w-5 text-blue-500" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-2xl font-bold text-primary dark:text-white">{{ $this->expensesDueSoon->total_count }}</p>
                            <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ __('Contas vencendo em 7 dias') }}</p>
                            @if($this->expensesDueSoon->total_count > 0)
                                <p class="truncate text-xs text-slate-400 tabular-nums dark:text-slate-500">
                                    <x-money :amount="$this->expensesDueSoon->total_amount" />
                                </p>
                            @endif
                        </div>
                    </a>

                    <a
                        href="{{ route('employee.expenses.list', ['filterStatus' => 'overdue']) }}"
                        class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 transition-colors hover:border-accent-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-accent-500/40"
                    >
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-50 dark:bg-red-400/10">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-red-500" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-2xl font-bold text-primary dark:text-white">{{ $this->expensesOverdue->total_count }}</p>
                            <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ __('Contas atrasadas') }}</p>
                            @if($this->expensesOverdue->total_count > 0)
                                <p class="truncate text-xs text-slate-400 tabular-nums dark:text-slate-500">
                                    <x-money :amount="$this->expensesOverdue->total_amount" />
                                </p>
                            @endif
                        </div>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Page content -->
        <div class="py-4 mx-auto">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <x-card>
                    <x-slot:content>
                        <div class="flex items-center justify-between">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 truncate">{{ __('Novos Pedidos') }}</dt>
                                <dd class="mt-1 text-3xl font-semibold text-sky-500">{{ $this->ordersCount }}</dd>
                            </dl>
                            <div
                                x-data="{
                                    init() {
                                        new ApexCharts($refs.chartElement, {
                                            series: [{
                                                name: 'Orders',
                                                data: @json($this->dailyOrders)
                                            }],
                                            chart: {
                                                type: 'bar',
                                                width: 100,
                                                height: 46,
                                                sparkline: {
                                                    enabled: true,
                                                }
                                            },
                                            colors: ['#0ea5e9'],
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '80%',
                                                }
                                            },
                                            xaxis: {
                                                crosshairs: {
                                                    width: 1,
                                                },
                                            },
                                            tooltip: {
                                                fixed: {
                                                    enabled: false,
                                                },
                                                x: {
                                                    show: false,
                                                },
                                                y: {
                                                    title: {
                                                        formatter: function (seriesName) {
                                                            return '';
                                                        }
                                                    }
                                                },
                                                marker: {
                                                    show: false,
                                                }
                                            }
                                        }).render();
                                    }
                                }"
                                class="md:hidden lg:block"
                            >
                                <div x-ref="chartElement"></div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:content>
                        <div class="flex items-center justify-between">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 truncate">{{ __('Vendidos') }}</dt>
                                <dd class="mt-1 text-3xl font-semibold text-sky-500">{{ $this->orderItemsCount }}</dd>
                            </dl>
                            <div
                                x-data="{
                                    init() {
                                        new ApexCharts($refs.chartElement, {
                                            series: [{
                                                name: 'Orders',
                                                data: @json($this->dailyorderItems)
                                            }],
                                            chart: {
                                                type: 'bar',
                                                width: 100,
                                                height: 46,
                                                sparkline: {
                                                    enabled: true,
                                                }
                                            },
                                            colors: ['#0ea5e9'],
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '80%',
                                                }
                                            },
                                            xaxis: {
                                                crosshairs: {
                                                    width: 1,
                                                },
                                            },
                                            tooltip: {
                                                fixed: {
                                                    enabled: false,
                                                },
                                                x: {
                                                    show: false,
                                                },
                                                y: {
                                                    title: {
                                                        formatter: function (seriesName) {
                                                            return '';
                                                        }
                                                    }
                                                },
                                                marker: {
                                                    show: false,
                                                }
                                            }
                                        }).render();
                                    }
                                }"
                                class="md:hidden lg:block"
                            >
                                <div x-ref="chartElement"></div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:content>
                        <div class="flex items-center justify-between">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 truncate">{{ __('Novos Clientes') }}</dt>
                                <dd class="mt-1 text-3xl font-semibold text-sky-500">{{ $this->customersCount }}</dd>
                            </dl>
                            <div
                                x-data="{
                                    init() {
                                        new ApexCharts($refs.chartElement, {
                                            series: [{
                                                name: 'Orders',
                                                data: @json($this->dailyCustomers)
                                            }],
                                            chart: {
                                                type: 'bar',
                                                width: 100,
                                                height: 46,
                                                sparkline: {
                                                    enabled: true,
                                                }
                                            },
                                            colors: ['#0ea5e9'],
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '80%',
                                                }
                                            },
                                            xaxis: {
                                                crosshairs: {
                                                    width: 1,
                                                },
                                            },
                                            tooltip: {
                                                fixed: {
                                                    enabled: false,
                                                },
                                                x: {
                                                    show: false,
                                                },
                                                y: {
                                                    title: {
                                                        formatter: function (seriesName) {
                                                            return '';
                                                        }
                                                    }
                                                },
                                                marker: {
                                                    show: false,
                                                }
                                            }
                                        }).render();
                                    }
                                }"
                                class="md:hidden lg:block"
                            >
                                <div x-ref="chartElement"></div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            </div>

            <div class="mt-5">
                <x-card>
                    <x-slot name="content">
                        <div
                            x-data="{
                                init() {
                                    const formatter = new Intl.NumberFormat('{{ config('app.locale') }}', {
                                        style: 'currency',
                                        currency: '{{ config('app.currency') }}',
                                    });
                                    new ApexCharts($refs.chartElement, {
                                        series: [{
                                            name: 'Amount',
                                            data: {{ json_encode($this->dailySalesReport['sales']) }}
                                        }],
                                        chart: {
                                            height: 350,
                                            type: 'area',
                                            toolbar: {
                                                show: false,
                                            }
                                        },
                                        grid: {
                                            show: true,
                                            borderColor: theme === 'dark' ? '#374151' : '#e2e8f0',
                                        },
                                        colors: ['#0ea5e9'],
                                        dataLabels: {
                                            enabled: false
                                        },
                                        stroke: {
                                            curve: 'smooth'
                                        },
                                        xaxis: {
                                            type: 'datetime',
                                            tooltip: {
                                                enabled: false
                                            },
                                            categories: {{ json_encode($this->dailySalesReport['days']) }},
                                            labels: {
                                                style: {
                                                    colors: theme === 'dark' ? '#e2e8f0' : '#111827',
                                                }
                                            },
                                        },
                                        yaxis: {
                                            labels: {
                                                formatter: function (value) {
                                                    return formatter.format(value);
                                                },
                                                style: {
                                                    colors: theme === 'dark' ? '#e2e8f0' : '#111827',
                                                }
                                            },
                                        },
                                        tooltip: {
                                            theme: 'dark',
                                            x: {
                                                format: 'dd/MM/yyyy'
                                            },
                                        },
                                        title: {
                                            text: 'Sales report',
                                            offsetX: 5,
                                            style: {
                                                color: theme === 'dark' ? '#e2e8f0' : '#111827',
                                                fontFamily: 'Inter',
                                                fontSize: '18px',
                                                fontWeight: '500',
                                            }
                                        }
                                    }).render();
                                }
                            }"
                            class="-mx-3 -mt-2 -mb-3"
                        >
                            <div x-ref="chartElement"></div>
                        </div>
                    </x-slot>
                </x-card>
            </div>

            <div class="mt-5 grid grid-cols-1 xl:grid-cols-2 gap-5">
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Pedidos Recentes') }}
                        </h3>
                    </x-slot>
                    <x-slot name="content">
                        <div class="-mx-4 -my-6 sm:-m-6">
                            <div class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <table class="min-w-full divide-y divide-slate-200 dark:divide-white/10">
                                        <thead class="bg-slate-50 dark:bg-white/5">
                                            <tr>
                                                <th class="py-3.5 px-4 sm:pl-6 font-medium text-left text-xs text-slate-500 tracking-wider uppercase dark:text-slate-200">
                                                    {{ __('ID') }}
                                                </th>
                                                <th class="py-3.5 px-4 font-medium text-left text-xs text-slate-500 tracking-wider uppercase dark:text-slate-200">
                                                    {{ __('Cliente') }}
                                                </th>
                                                <th class="py-3.5 px-4 font-medium text-right text-xs text-slate-500 tracking-wider uppercase dark:text-slate-200">
                                                    {{ __('Total') }}
                                                </th>
                                                <th class="py-3.5 px-4 sm:pr-6 font-medium text-right text-xs text-slate-500 tracking-wider uppercase dark:text-slate-200">
                                                    {{ __('Data') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 dark:divide-white/20">
                                            @forelse($this->recentOrders as $order)
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 px-4 sm:px-6 text-sm font-medium text-slate-900 dark:text-slate-400">
                                                        <a
                                                            href="{{ route('employee.orders.detail', $order) }}"
                                                            class="hover:text-blue-500 dark:hover:text-sky-400"
                                                        >
                                                            {{ $order->id }}
                                                        </a>
                                                    </td>
                                                    <td class="whitespace-nowrap py-4 px-4 text-sm font-medium text-slate-900 dark:text-slate-400">
                                                        @if($order->customer)
                                                            <a
                                                                href="{{ route('employee.customers.detail', $order->customer) }}"
                                                                class="hover:text-blue-500 dark:hover:text-sky-400"
                                                            >
                                                                {{ $order->customer->name }}
                                                            </a>
                                                        @else
                                                            {{ __('Nenhum Cliente') }}
                                                        @endif
                                                    </td>
                                                    <td class="whitespace-nowrap py-4 px-4 text-sm text-slate-900 text-right tabular-nums dark:text-slate-400">
                                                        <x-money
                                                            :amount="$order->total"
                                                            :currency="config('app.currency')"
                                                        />
                                                    </td>
                                                    <td class="whitespace-nowrap py-4 px-4 sm:pr-6 text-sm text-slate-900 text-right tabular-nums dark:text-slate-400">
                                                        {{ $order->created_at->format('Y-m-d') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="whitespace-nowrap py-4 px-4 text-sm text-slate-500 text-center"
                                                    >
                                                        {{ __('Sem registros.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-card>

                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Mais Vendidos') }}
                        </h3>
                    </x-slot>
                    <x-slot name="content">
                        <div class="-mx-4 -my-6 sm:-m-6">
                            <div class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <table class="min-w-full divide-y divide-slate-200 dark:divide-white/10">
                                        <thead class="bg-slate-50 dark:bg-white/5">
                                            <tr>
                                                <th class="py-3.5 px-4 sm:pl-6 font-medium text-left text-xs text-slate-500 tracking-wider uppercase dark:text-slate-400">
                                                    {{ __('Nome') }}
                                                </th>
                                                <th class="py-3.5 px-4 sm:pr-6 font-medium text-right text-xs text-slate-500 tracking-wider uppercase dark:text-slate-400">
                                                    {{ __('Valor') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                                            @forelse($this->topSellingProducts as $product)
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 px-4 sm:pl-6 font-medium text-sm text-slate-900 dark:text-slate-400">
                                                        <a
                                                            href="{{ route('employee.products.detail', $product->id) }}"
                                                            class="hover:text-sky-500 dark:hover:text-sky-400"
                                                        >
                                                            {{ $product->name }}
                                                        </a>
                                                    </td>
                                                    <td class="whitespace-nowrap py-4 px-4 sm:px-6 text-sm text-slate-900 text-right tabular-nums dark:text-slate-400">
                                                        <x-money
                                                            :amount="$product->total_sales"
                                                            :currency="config('app.currency')"
                                                        />
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="whitespace-nowrap py-4 px-4 sm:pr-6 text-sm text-slate-500 text-center dark:text-slate-400"
                                                    >
                                                        {{ __('Sem registros.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-card>
            </div>

            <div class="mt-5" id="low-stock-alert">
                <livewire:employee.product.components.low-stock-alert />
            </div>
        </div>
        
    </div>

</div>