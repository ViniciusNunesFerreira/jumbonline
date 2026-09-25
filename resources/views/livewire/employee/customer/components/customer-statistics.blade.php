<div>
    <x-card>
        <x-slot:content>
            <dl class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 md:grid-cols-4 md:divide-y-0 md:divide-x dark:divide-white/5">
                <div class="py-3 sm:pr-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('LTV (receita líquida)') }}</dt>
                    <dd class="mt-1 text-2xl font-bold text-primary dark:text-white">
                        <x-money :amount="$customer->ltv_total" />
                    </dd>
                </div>

                <div class="py-3 sm:px-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Pedidos pagos') }}</dt>
                    <dd class="mt-1 text-2xl font-bold text-primary dark:text-white">
                        {{ $customer->paid_orders_count }}
                    </dd>
                </div>

                <div class="py-3 sm:px-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Ticket médio líquido') }}</dt>
                    <dd class="mt-1 text-2xl font-bold text-primary dark:text-white">
                        <x-money :amount="$this->ticketMedio" />
                    </dd>
                </div>

                <div class="py-3 sm:pl-4 sm:py-1">
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Última compra') }}</dt>
                    <dd class="mt-1 text-lg font-semibold text-primary dark:text-white">
                        {{ $customer->last_order_at?->diffForHumans() ?? __('Nunca comprou') }}
                    </dd>
                </div>
            </dl>
        </x-slot:content>
    </x-card>
</div>