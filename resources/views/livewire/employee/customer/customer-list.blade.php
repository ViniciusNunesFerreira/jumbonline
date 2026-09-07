{{-- resources/views/livewire/employee/customer/customer-list.blade.php (substituição completa) --}}

<div>
    <x-slot:title>
        {{ __('Clientes') }}
    </x-slot:title>

    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">
                {{ __('Clientes') }}
            </h1>
        </div>
        @if($customers->count() || $this->hasActiveFilters || $search)
            <div class="mt-4 flex sm:mt-0 sm:ml-4">
                
                    <a href="{{ route('employee.customers.create') }}"
                    class="btn btn-primary block w-full order-0 sm:order-1 sm:ml-3"
                >
                    {{ __('Add cliente') }}
                </a>
            </div>
        @endif
    </div>

    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @if(!$customers->count() && !$search && !$this->hasActiveFilters)
            <x-card>
                <x-slot:content>
                    <div class="max-w-lg mx-auto text-center">
                        <x-heroicon-o-user-group class="mx-auto h-12 w-12 text-slate-400" />

                        <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Tudo relacionado ao cliente em um único lugar') }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Ao adicionar clientes, você poderá atualizar seus detalhes, obter um resumo do histórico de pedidos e muito mais.') }}
                        </p>

                        <div class="mt-6">
                            
                                <a href="{{ route('employee.customers.create') }}"
                                class="btn btn-primary"
                            >
                                <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                {{ __('Add cliente') }}
                            </a>
                        </div>
                    </div>
                </x-slot:content>
            </x-card>
        @else
            <x-card class="overflow-hidden">
                <x-slot:header>
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div
                            x-data="{ search: @entangle('search')}"
                            class="relative max-w-sm text-slate-400 focus-within:text-slate-600 dark:focus-within:text-slate-200"
                        >
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                            </div>
                            <x-input
                                wire:model.debounce.500ms="search"
                                type="text"
                                class="placeholder-slate-500 w-full pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                                ::class="{ 'pr-10' : search }"
                                placeholder="{{ __('Filtrar clientes') }}"
                            />
                            <button
                                x-show="search.length"
                                x-on:click="search = ''"
                                type="button"
                                class="absolute inset-y-0 right-0 flex items-center pr-3"
                            >
                                <x-heroicon-s-x-circle class="w-5 h-5 text-slate-500 hover:text-slate-600 dark:hover:text-slate-400" />
                            </button>
                        </div>

                        <div class="flex flex-wrap items-end gap-3">
                            <div>
                                <x-standalone-label>{{ __('Unidade prisional') }}</x-standalone-label>
                                <x-select wire:model="filterPrisonUnit" class="mt-1 h-10 text-sm">
                                    <option value="">{{ __('Todas') }}</option>
                                    @foreach($prisonUnits as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div>
                                <x-standalone-label>{{ __('Status') }}</x-standalone-label>
                                <x-select wire:model="filterStatus" class="mt-1 h-10 text-sm">
                                    <option value="">{{ __('Todos') }}</option>
                                    <option value="active">{{ __('Ativo') }}</option>
                                    <option value="banned">{{ __('Banido') }}</option>
                                </x-select>
                            </div>

                            <div>
                                <x-standalone-label>{{ __('LTV mín.') }}</x-standalone-label>
                                <x-input
                                    wire:model.debounce.500ms="filterLtvMin"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 h-10 w-28 text-sm"
                                    placeholder="0,00"
                                />
                            </div>

                            <div>
                                <x-standalone-label>{{ __('LTV máx.') }}</x-standalone-label>
                                <x-input
                                    wire:model.debounce.500ms="filterLtvMax"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 h-10 w-28 text-sm"
                                    placeholder="9999,99"
                                />
                            </div>

                            @if($this->hasActiveFilters)
                                <button
                                    wire:click="resetFilters"
                                    type="button"
                                    class="btn btn-default btn-xs h-10"
                                >
                                    {{ __('Limpar filtros') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="relative overflow-hidden">
                                <div
                                    wire:loading.delay
                                    class="absolute inset-0 z-10 bg-slate-100/50 dark:bg-slate-800/50"
                                >
                                    <div
                                        wire:loading.flex
                                        class="h-full w-screen items-center justify-center sm:w-auto"
                                    >
                                        <div class="m-auto flex items-center space-x-2">
                                            <p class="text-sm dark:text-slate-200">{{ 'Buscando clientes...' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10">
                                    <thead class="border-t border-slate-200 bg-slate-50 dark:border-slate-200/10 dark:bg-slate-800/75">
                                        <tr>
                                            <th scope="col" class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                <x-input
                                                    wire:model="selectPage"
                                                    type="checkbox"
                                                    class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                />
                                            </th>
                                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200">
                                                {{ __('Nome do Cliente') }}
                                            </th>
                                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200">
                                                {{ __('Pedidos pagos') }}
                                            </th>
                                            <th scope="col" class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200">
                                                {{ __('Última compra') }}
                                            </th>
                                            <th scope="col" class="pl-3 pr-4 py-4 text-right text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap sm:pr-6 dark:text-slate-200">
                                                {{ __('LTV') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                                        @forelse($customers as $customer)
                                            <tr
                                                wire:loading.class.delay="opacity-50"
                                                class="relative hover:bg-slate-50 dark:hover:bg-slate-800/75"
                                            >
                                                <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                    @if(in_array($customer->id, $selected))
                                                        <div class="absolute inset-y-0 left-0 w-0.5 bg-sky-500 dark:bg-sky-400"></div>
                                                    @endif
                                                    <x-input
                                                        wire:model="selected"
                                                        wire:key="checkbox-{{ $customer->id }}"
                                                        type="checkbox"
                                                        value="{{ $customer->id }}"
                                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                    />
                                                </td>
                                                <td class="relative px-3 py-4 font-medium text-sm text-slate-900 text-left whitespace-nowrap dark:text-slate-200">
                                                    <div class="flex items-center">
                                                        <div class="h-10 w-10 flex-shrink-0">
                                                            <img
                                                                class="h-10 w-10 rounded-full bg-slate-200 object-center object-cover"
                                                                src="{{ $customer->getFirstMediaUrl('avatar') }}"
                                                                alt="{{ $customer->name }}"
                                                            >
                                                        </div>
                                                        <div class="ml-4">
                                                            
                                                                <a href="{{ route('employee.customers.detail', $customer) }}"
                                                                class="inline-flex items-center truncate hover:text-sky-600 dark:hover:text-sky-400"
                                                            >
                                                                {{ $customer->name }}
                                                            </a>
                                                            @if($customer->banned_at)
                                                                <x-badge type="danger" size="xs" class="ml-2">{{ __('Banido') }}</x-badge>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                    {{ trans_choice(':count pedido pago|:count pedidos pagos', $customer->paid_orders_count) }}
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                    {{ $customer->last_order_at?->diffForHumans() ?? __('Nunca comprou') }}
                                                </td>
                                                <td class="pl-3 pr-4 py-4 text-right text-sm text-slate-500 whitespace-nowrap sm:pr-6 dark:text-slate-400">
                                                    <x-money :amount="$customer->ltv_total" />
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="px-3 py-4 text-sm text-slate-500 text-center whitespace-nowrap dark:text-slate-400" colspan="5">
                                                    <div class="max-w-lg mx-auto text-center">
                                                        <x-heroicon-o-magnifying-glass class="inline-block w-10 h-10 text-slate-400 dark:text-slate-300" />
                                                        <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-200">
                                                            {{ __('Nenhum cliente encontrado') }}
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
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>