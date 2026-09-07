<div>
    <x-card>
        <x-slot:content>
            <dl class="grid grid-cols-1 divide-y divide-slate-200 sm:grid-cols-2 md:grid-cols-4 md:divide-y-0 md:divide-x dark:divide-white/10">
                <div class="py-3 sm:pr-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('LTV (receita líquida)') }}</dt>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-slate-900 dark:text-slate-200">
                            <x-money :amount="$customer->ltv_total" />
                        </div>
                    </dd>
                </div>

                <div class="py-3 sm:px-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Pedidos pagos') }}</dt>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-slate-900 dark:text-slate-200">
                            {{ $customer->paid_orders_count }}
                        </div>
                    </dd>
                </div>

                <div class="py-3 sm:px-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Ticket médio líquido') }}</dt>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-slate-900 dark:text-slate-200">
                            <x-money :amount="$this->ticketMedio" />
                        </div>
                    </dd>
                </div>

                <div class="py-3 sm:pl-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Última compra') }}</dt>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-lg font-semibold text-slate-900 dark:text-slate-200">
                            {{ $customer->last_order_at?->diffForHumans() ?? __('Nunca comprou') }}
                        </div>
                    </dd>
                </div>
            </dl>
        </x-slot:content>
    </x-card>
</div>