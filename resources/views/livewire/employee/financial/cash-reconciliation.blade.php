<div>
    <x-slot:title>{{ __('Conciliação de caixa') }}</x-slot:title>

    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="flex items-center gap-2">
            <a href="{{ route('employee.financial.dashboard') }}" class="btn btn-default btn-xs">
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">{{ __('Conciliação de caixa (PDV)') }}</h1>
        </div>
        <div class="mt-4 flex gap-2 sm:mt-0">
            <x-input wire:model="from" type="date" class="h-9 text-sm" />
            <x-input wire:model="to" type="date" class="h-9 text-sm" />
        </div>
    </div>

    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <x-card>
                <x-slot:content>
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Volume PDV (calculado)') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900 dark:text-slate-200"><x-money :amount="$pdvTotals['calculated']" /></dd>
                </x-slot:content>
            </x-card>
            <x-card>
                <x-slot:content>
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Volume site (Mercado Pago)') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900 dark:text-slate-200"><x-money :amount="$siteRevenue" /></dd>
                </x-slot:content>
            </x-card>
            <x-card>
                <x-slot:content>
                    <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Sessões com diferença') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold {{ $pdvTotals['sessions_with_diff'] > 0 ? 'text-amber-600' : 'text-slate-900 dark:text-slate-200' }}">
                        {{ $pdvTotals['sessions_with_diff'] }}
                    </dd>
                </x-slot:content>
            </x-card>
        </div>

        <x-card class="overflow-hidden">
            <x-slot:header>
                <h3 class="font-display font-medium text-base text-slate-900 dark:text-slate-200">{{ __('Fechamentos de caixa no período') }}</h3>
            </x-slot:header>
            <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10">
                        <thead class="bg-slate-50 dark:bg-slate-800/75">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Operador') }}</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Fechado em') }}</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Esperado') }}</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Contado') }}</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Diferença') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                            @forelse($sessions as $session)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-200">{{ $session->employee?->name }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $session->closed_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-slate-500 dark:text-slate-400"><x-money :amount="$session->calculated_balance" /></td>
                                    <td class="px-4 py-3 text-sm text-right text-slate-500 dark:text-slate-400"><x-money :amount="$session->closing_balance" /></td>
                                    <td class="px-4 py-3 text-sm text-right font-medium {{ $session->difference != 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                        <x-money :amount="$session->difference" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400">
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