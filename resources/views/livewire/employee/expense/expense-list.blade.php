<div>
    <x-slot:title>
        {{ __('Contas a Pagar') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Contas a Pagar') }}
                </h1>
            </div>
            @if($expenses->count() || $this->hasActiveFilters || $search)
                <div class="mt-4 flex flex-wrap gap-2 sm:mt-0 sm:ml-4">
                    <a href="{{ route('employee.expenses.calendar') }}" class="btn btn-default btn-xs !rounded-xl">
                        <x-heroicon-m-calendar-days class="mr-1 h-4 w-4" />
                        {{ __('Calendário') }}
                    </a>
                    <a href="{{ route('employee.expenses.create') }}"
                        class="btn btn-primary block w-full order-0 sm:order-1 sm:ml-3"
                    >
                        {{ __('Nova conta') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="mt-6">
            @if(!$expenses->count() && !$search && !$this->hasActiveFilters)
                <x-card>
                    <x-slot:content>
                        <div class="max-w-lg mx-auto text-center py-6">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                <x-heroicon-o-banknotes class="h-7 w-7 text-accent-500" />
                            </span>

                            <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Nenhuma conta a pagar cadastrada ainda') }}
                            </h3>

                            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Cadastre despesas como aluguel, fornecedores, folha de pagamento e contas de consumo para acompanhar vencimentos e pagamentos num só lugar.') }}
                            </p>

                            <div class="mt-6">
                                <a href="{{ route('employee.expenses.create') }}" class="btn btn-primary">
                                    <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                    {{ __('Nova conta') }}
                                </a>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            @else
                <x-card class="overflow-hidden">
                    <x-slot:header>
                        @if(count($selected))
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium text-primary dark:text-slate-200">
                                    {{ trans_choice(':count conta selecionada|:count contas selecionadas', count($selected)) }}
                                </p>
                                <div class="flex items-center gap-2">
                                    @if($expenses->total() > $expenses->count())
                                        <button wire:click="$toggle('selectAll')" type="button" class="btn btn-link text-xs">
                                            {{ $selectAll ? __('Limpar seleção') : __('Selecionar todas as :count', ['count' => $expenses->total()]) }}
                                        </button>
                                    @endif
                                    <button wire:click="$set('showDeleteConfirmationModal', true)" type="button" class="btn btn-outline-danger btn-xs !rounded-xl">
                                        <x-heroicon-m-trash class="w-4 h-4 mr-1" />
                                        {{ __('Excluir') }}
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                                <div
                                    x-data="{ search: @entangle('search')}"
                                    class="relative max-w-sm text-slate-400 focus-within:text-primary dark:focus-within:text-slate-200"
                                >
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                                    </div>
                                    <x-input
                                        wire:model.debounce.500ms="search"
                                        type="text"
                                        class="placeholder-slate-400 w-full rounded-xl pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                                        ::class="{ 'pr-10' : search }"
                                        placeholder="{{ __('Buscar por descrição ou favorecido') }}"
                                    />
                                    <button
                                        x-show="search.length"
                                        x-on:click="search = ''"
                                        type="button"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3"
                                    >
                                        <x-heroicon-s-x-circle class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-400" />
                                    </button>
                                </div>

                                <div
                                    x-data="{
                                        dueFrom: @entangle('filterDueFrom'),
                                        dueTo: @entangle('filterDueTo'),
                                    }"
                                    x-init="
                                        flatpickr($refs.dueFrom, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', disableMobile: true, defaultDate: dueFrom || null, onChange: (d, s) => dueFrom = s });
                                        flatpickr($refs.dueTo, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', disableMobile: true, defaultDate: dueTo || null, onChange: (d, s) => dueTo = s });
                                    "
                                    class="flex flex-wrap items-end gap-3"
                                >
                                    <div>
                                        <x-standalone-label>{{ __('Status') }}</x-standalone-label>
                                        <x-select wire:model="filterStatus" class="mt-1 !h-10 rounded-xl text-sm">
                                            <option value="">{{ __('Todos') }}</option>
                                            @foreach(\App\Enums\ExpenseStatus::cases() as $status)
                                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>

                                    <div>
                                        <x-standalone-label>{{ __('Categoria') }}</x-standalone-label>
                                        <x-select wire:model="filterCategory" class="mt-1 !h-10 rounded-xl text-sm">
                                            <option value="">{{ __('Todas') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </x-select>
                                    </div>

                                    <div wire:ignore class="w-32">
                                        <x-standalone-label>{{ __('Vencimento de') }}</x-standalone-label>
                                        <x-input x-ref="dueFrom" type="text" class="mt-1 h-10 w-full rounded-xl text-sm" placeholder="{{ __('Início') }}" />
                                    </div>

                                    <div wire:ignore class="w-32">
                                        <x-standalone-label>{{ __('Vencimento até') }}</x-standalone-label>
                                        <x-input x-ref="dueTo" type="text" class="mt-1 h-10 w-full rounded-xl text-sm" placeholder="{{ __('Fim') }}" />
                                    </div>

                                    @if($this->hasActiveFilters)
                                        <button
                                            wire:click="resetFilters"
                                            type="button"
                                            class="btn btn-default btn-xs h-10 !rounded-xl"
                                        >
                                            {{ __('Limpar filtros') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="relative overflow-hidden">
                                    <div
                                        wire:loading.delay
                                        class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[1px] dark:bg-slate-900/60"
                                    >
                                        <div wire:loading.flex class="h-full w-screen items-center justify-center sm:w-auto">
                                            <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Carregando contas...') }}</p>
                                        </div>
                                    </div>
                                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                                        <thead>
                                            <tr class="border-b border-slate-100 dark:border-white/5">
                                                <th scope="col" class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                    <x-input
                                                        wire:model="selectPage"
                                                        type="checkbox"
                                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                    />
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Vencimento') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Descrição') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Categoria') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Status') }}
                                                </th>
                                                <th scope="col" class="pl-3 pr-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap sm:pr-6 dark:text-slate-500">
                                                    {{ __('Valor') }}
                                                </th>
                                                <th scope="col" class="pl-3 pr-4 py-3.5 sm:pr-6"><span class="sr-only">{{ __('Editar') }}</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @forelse($expenses as $expense)
                                                <tr
                                                    wire:key="expense-row-{{ $expense->id }}"
                                                    wire:loading.class.delay="opacity-50"
                                                    class="relative transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]"
                                                >
                                                    <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                        @if(in_array($expense->id, $selected))
                                                            <div class="absolute inset-y-0 left-0 w-0.5 bg-accent-500"></div>
                                                        @endif
                                                        <x-input
                                                            wire:model="selected"
                                                            wire:key="checkbox-{{ $expense->id }}"
                                                            type="checkbox"
                                                            value="{{ $expense->id }}"
                                                            class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                        />
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap tabular-nums dark:text-slate-400">
                                                        {{ $expense->due_date->format('d/m/Y') }}
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        <a href="{{ route('employee.expenses.detail', $expense) }}" class="font-medium text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400">
                                                            {{ $expense->description }}
                                                        </a>
                                                        <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">
                                                            {{ $expense->payee->name }}
                                                        </p>
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                        {{ $expense->category->name ?? __('Sem categoria') }}
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium" style="background-color: {{ $expense->status->color() }}1A; color: {{ $expense->status->color() }};">
                                                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $expense->status->color() }}"></span>
                                                            {{ $expense->status->label() }}
                                                        </span>
                                                    </td>
                                                    <td class="relative pl-3 pr-4 py-4 text-sm text-right font-semibold text-primary whitespace-nowrap tabular-nums sm:pr-6 dark:text-slate-200">
                                                        <x-money :amount="$expense->amount" />
                                                    </td>
                                                    <td class="relative pl-3 pr-4 py-4 text-right whitespace-nowrap sm:pr-6">
                                                        <a href="{{ route('employee.expenses.detail', $expense) }}" class="inline-flex text-slate-400 hover:text-accent-500">
                                                            <x-heroicon-o-pencil-square class="h-5 w-5" />
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="px-3 py-12 text-sm text-center whitespace-nowrap" colspan="7">
                                                        <div class="max-w-lg mx-auto text-center">
                                                            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 dark:bg-white/5">
                                                                <x-heroicon-o-magnifying-glass class="h-6 w-6 text-slate-400" />
                                                            </span>
                                                            <h3 class="mt-3 text-sm font-semibold text-primary dark:text-slate-200">
                                                                {{ __('Nenhuma conta encontrada') }}
                                                            </h3>
                                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                                {{ __('Tente alterar os filtros ou o termo de pesquisa') }}
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <div class="mt-6">
                    {{ $expenses->links() }}
                </div>

                <x-modal-alert wire:model="showDeleteConfirmationModal">
                    <x-slot:title>
                        {{ __('Por favor, confirme sua ação!') }}
                    </x-slot:title>
                    <x-slot:content>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ trans_choice('Tem certeza de que deseja excluir :count conta?|Tem certeza de que deseja excluir :count contas?', count($selected)) }}
                            {{ __('Essa ação não pode ser desfeita!') }}
                        </p>
                    </x-slot:content>
                    <x-slot:footer>
                        <button wire:click.prevent="deleteSelected" class="btn btn-danger w-full sm:ml-3 sm:w-auto">
                            {{ __('Excluir') }}
                        </button>
                        <button x-on:click.prevent="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                            {{ __('Cancelar') }}
                        </button>
                    </x-slot:footer>
                </x-modal-alert>
            @endif
        </div>
    </div>
</div>