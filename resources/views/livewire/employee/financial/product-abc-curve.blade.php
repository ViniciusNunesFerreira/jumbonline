<div>
    <x-slot:title>{{ __('Curva ABC') }}</x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('employee.financial.dashboard') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">{{ __('Curva ABC de produtos') }}</h1>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            <x-card>
                <x-slot:content>
                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <x-standalone-label>{{ __('De') }}</x-standalone-label>
                            <x-input wire:model="from" type="date" class="mt-1 !h-10 rounded-xl text-sm" />
                        </div>
                        <div>
                            <x-standalone-label>{{ __('Até') }}</x-standalone-label>
                            <x-input wire:model="to" type="date" class="mt-1 !h-10 rounded-xl text-sm" />
                        </div>
                        <div>
                            <x-standalone-label>{{ __('Unidade prisional') }}</x-standalone-label>
                            <x-select wire:model="prisonUnitId" class="mt-1 !h-10 rounded-xl text-sm">
                                <option value="">{{ __('Todas') }}</option>
                                @foreach($prisonUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </x-select>
                        </div>
                    </div>
                </x-slot:content>
            </x-card>

            <x-card class="overflow-hidden">
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-white/5">
                                    <th class="px-4 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Produto') }}</th>
                                    <th class="px-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Receita') }}</th>
                                    <th class="px-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Qtd.') }}</th>
                                    <th class="px-4 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 w-48">{{ __('% Acumulado') }}</th>
                                    <th class="px-4 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Classe') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse($curve as $row)
                                    <tr class="transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]">
                                        <td class="px-4 py-3.5 text-sm font-medium text-primary dark:text-slate-200">{{ $row['name'] }}</td>
                                        <td class="px-4 py-3.5 text-sm text-right text-slate-500 dark:text-slate-400"><x-money :amount="$row['revenue']" /></td>
                                        <td class="px-4 py-3.5 text-sm text-right text-slate-500 dark:text-slate-400">{{ $row['quantity'] }}</td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-2">
                                                <div class="h-1.5 flex-1 rounded-full bg-slate-100 dark:bg-white/5">
                                                    <div @class(['h-1.5 rounded-full', 'bg-emerald-500' => $row['class'] === 'A', 'bg-amber-500' => $row['class'] === 'B', 'bg-slate-300' => $row['class'] === 'C']) style="width: {{ $row['cumulative_percent'] }}%"></div>
                                                </div>
                                                <span class="w-10 shrink-0 text-xs text-slate-400">{{ $row['cumulative_percent'] }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <x-badge :type="$row['class'] === 'A' ? 'success' : ($row['class'] === 'B' ? 'warning' : 'default')" size="xs">
                                                {{ $row['class'] }}
                                            </x-badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-sm text-center text-slate-500 dark:text-slate-400">
                                            {{ __('Nenhuma venda no período selecionado.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</div>