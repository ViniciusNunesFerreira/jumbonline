<div>
    <x-slot:title>{{ __('Curva ABC') }}</x-slot:title>

    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="flex items-center gap-2">
            <a href="{{ route('employee.financial.dashboard') }}" class="btn btn-default btn-xs">
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">{{ __('Curva ABC de produtos') }}</h1>
        </div>
    </div>

    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <x-card>
            <x-slot:content>
                <div class="flex flex-wrap items-end gap-4">
                    <div>
                        <x-standalone-label>{{ __('De') }}</x-standalone-label>
                        <x-input wire:model="from" type="date" class="mt-1 h-10 text-sm" />
                    </div>
                    <div>
                        <x-standalone-label>{{ __('Até') }}</x-standalone-label>
                        <x-input wire:model="to" type="date" class="mt-1 h-10 text-sm" />
                    </div>
                    <div>
                        <x-standalone-label>{{ __('Unidade prisional') }}</x-standalone-label>
                        <x-select wire:model="prisonUnitId" class="mt-1 h-10 text-sm">
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
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10">
                        <thead class="bg-slate-50 dark:bg-slate-800/75">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Produto') }}</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Receita') }}</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Qtd.') }}</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('% Acumulado') }}</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-slate-200">{{ __('Classe') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                            @forelse($curve as $row)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-200">{{ $row['name'] }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-slate-500 dark:text-slate-400"><x-money :amount="$row['revenue']" /></td>
                                    <td class="px-4 py-3 text-sm text-right text-slate-500 dark:text-slate-400">{{ $row['quantity'] }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-slate-500 dark:text-slate-400">{{ $row['cumulative_percent'] }}%</td>
                                    <td class="px-4 py-3 text-center">
                                        <x-badge :type="$row['class'] === 'A' ? 'success' : ($row['class'] === 'B' ? 'warning' : 'default')" size="xs">
                                            {{ $row['class'] }}
                                        </x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400">
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