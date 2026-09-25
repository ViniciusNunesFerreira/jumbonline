<div>
    <x-slot:title>{{ __('Conciliação de caixa') }}</x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('employee.financial.dashboard') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">{{ __('Conciliação de caixa (PDV)') }}</h1>
            </div>
            <div class="mt-4 flex gap-2 sm:mt-0">
                <x-input wire:model="from" type="date" class="!h-10 rounded-xl text-sm" />
                <x-input wire:model="to" type="date" class="!h-10 rounded-xl text-sm" />
            </div>
        </div>

        <div class="mt-6 space-y-6">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <x-card>
                    <x-slot:content>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Volume PDV (calculado)') }}</dt>
                        <dd class="mt-2 text-2xl font-bold text-primary dark:text-white"><x-money :amount="$pdvTotals['calculated']" /></dd>
                    </x-slot:content>
                </x-card>
                <x-card>
                    <x-slot:content>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Volume site (Mercado Pago)') }}</dt>
                        <dd class="mt-2 text-2xl font-bold text-primary dark:text-white"><x-money :amount="$siteRevenue" /></dd>
                    </x-slot:content>
                </x-card>
                <x-card @class(['ring-2 ring-warning-400' => $pdvTotals['sessions_with_diff'] > 0])>
                    <x-slot:content>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Sessões com diferença') }}</dt>
                        <dd @class(['mt-2 text-2xl font-bold', 'text-warning-600' => $pdvTotals['sessions_with_diff'] > 0, 'text-primary dark:text-white' => $pdvTotals['sessions_with_diff'] == 0])>
                            {{ $pdvTotals['sessions_with_diff'] }}
                        </dd>
                    </x-slot:content>
                </x-card>
            </div>

            <x-card class="overflow-hidden">
                <x-slot:header>
                    <h3 class="font-semibold text-sm text-primary dark:text-slate-200">{{ __('Fechamentos de caixa no período') }}</h3>
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-white/5">
                                    <th class="px-4 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Operador') }}</th>
                                    <th class="px-4 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Fechado em') }}</th>
                                    <th class="px-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Esperado') }}</th>
                                    <th class="px-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Contado') }}</th>
                                    <th class="px-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Diferença') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse($sessions as $session)
                                    <tr class="transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]">
                                        <td class="px-4 py-3.5 text-sm font-medium text-primary dark:text-slate-200">{{ $session->employee?->name }}</td>
                                        <td class="px-4 py-3.5 text-sm text-slate-500 dark:text-slate-400">{{ $session->closed_at?->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3.5 text-sm text-right text-slate-500 dark:text-slate-400"><x-money :amount="$session->calculated_balance" /></td>
                                        <td class="px-4 py-3.5 text-sm text-right text-slate-500 dark:text-slate-400"><x-money :amount="$session->closing_balance" /></td>
                                        <td @class(['px-4 py-3.5 text-sm text-right font-semibold', 'text-warning-600' => $session->difference != 0, 'text-emerald-600' => $session->difference == 0])>
                                            <x-money :amount="$session->difference" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-sm text-center text-slate-500 dark:text-slate-400">
                                            {{ __('Nenhum fechamento de caixa no período.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot:content>
            </x-card>

            <div>{{ $sessions->links() }}</div>
        </div>
    </div>
</div>