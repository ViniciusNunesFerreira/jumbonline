<div>
    <x-slot:title>
        {{ __('Calendário de Vencimentos') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Calendário de Vencimentos') }}
                </h1>
            </div>
            <div class="mt-4 flex items-center gap-2 sm:mt-0">
                <button wire:click="previousMonth" type="button" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-chevron-left class="h-4 w-4" />
                </button>
                <button wire:click="goToToday" type="button" class="btn btn-default btn-xs !rounded-xl">
                    {{ __('Hoje') }}
                </button>
                <button wire:click="nextMonth" type="button" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-chevron-right class="h-4 w-4" />
                </button>
                <a href="{{ route('employee.expenses.list') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-queue-list class="mr-1 h-4 w-4" />
                    {{ __('Ver em lista') }}
                </a>
            </div>
        </div>

        <p class="mt-1 text-lg font-semibold text-primary dark:text-slate-200">
            {{ ucfirst($month->translatedFormat('F \d\e Y')) }}
        </p>

        <div class="mt-6">
            <x-card class="overflow-hidden">
                <x-slot:content class="-mx-4 -my-5 p-0 sm:-mx-6">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[720px] table-fixed border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-white/5">
                                    @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $weekday)
                                        <th class="w-[14.28%] px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                            {{ __($weekday) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($weeks as $week)
                                    <tr class="divide-x divide-slate-100 border-b border-slate-100 dark:divide-white/5 dark:border-white/5">
                                        @foreach($week as $day)
                                            <td class="h-28 align-top p-1.5 sm:h-32">
                                                @if($day)
                                                    @php
                                                        $dayKey = $day->format('Y-m-d');
                                                        $dayExpenses = $expensesByDay->get($dayKey, collect());
                                                        $isToday = $day->isToday();
                                                    @endphp
                                                    <div class="flex h-full flex-col">
                                                        <span
                                                            @class([
                                                                'mb-1 inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full text-xs font-semibold',
                                                                'bg-accent-500 text-white' => $isToday,
                                                                'text-slate-500 dark:text-slate-400' => ! $isToday,
                                                            ])
                                                        >
                                                            {{ $day->day }}
                                                        </span>
                                                        <div class="space-y-1 overflow-y-auto">
                                                            @foreach($dayExpenses->take(3) as $expense)
                                                                <a
                                                                    href="{{ route('employee.expenses.detail', $expense) }}"
                                                                    class="block truncate rounded-md px-1.5 py-1 text-[11px] leading-tight transition-colors"
                                                                    style="background-color: {{ $expense->status->color() }}1A; color: {{ $expense->status->color() }};"
                                                                    title="{{ $expense->description }} — {{ $expense->payee->name }}"
                                                                >
                                                                    {{ $expense->description }}
                                                                </a>
                                                            @endforeach
                                                            @if($dayExpenses->count() > 3)
                                                                <p class="px-1.5 text-[11px] text-slate-400">
                                                                    {{ __('+:count mais', ['count' => $dayExpenses->count() - 3]) }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</div>