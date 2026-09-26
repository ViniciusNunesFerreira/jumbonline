<div>
    <div class="sm:flex sm:items-center sm:justify-end">
        @if($discounts->count())
            <a href="{{ route('employee.discounts.create') }}" class="btn btn-primary">
                {{ __('Criar desconto') }}
            </a>
        @endif
    </div>

    <div class="mt-4">
        @if(!$discounts->count() && !$search)
            <x-card>
                <x-slot:content>
                    <div class="max-w-lg mx-auto text-center py-6">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                            <x-heroicon-o-ticket class="h-7 w-7 text-accent-500" />
                        </span>

                        <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Gerencie descontos e promoções') }}
                        </h3>

                        <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Crie promoções automáticas ou códigos promocionais pra kits específicos, grupos ou o pedido inteiro.') }}
                        </p>

                        <div class="mt-6">
                            <a href="{{ route('employee.discounts.create') }}" class="btn btn-primary">
                                <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                {{ __('Criar desconto') }}
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
                                {{ trans_choice(':count desconto selecionado|:count descontos selecionados', count($selected)) }}
                            </p>
                            <div class="flex items-center gap-2">
                                @if($discounts->total() > $discounts->count())
                                    <button wire:click="$toggle('selectAll')" type="button" class="btn btn-link text-xs">
                                        {{ $selectAll ? __('Limpar seleção') : __('Selecionar todos os :count', ['count' => $discounts->total()]) }}
                                    </button>
                                @endif
                                <button wire:click="$set('showDeleteConfirmationModal', true)" type="button" class="btn btn-outline-danger btn-xs !rounded-xl">
                                    <x-heroicon-m-trash class="w-4 h-4 mr-1" />
                                    {{ __('Excluir') }}
                                </button>
                            </div>
                        </div>
                    @else
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
                                placeholder="{{ __('Filtrar descontos') }}"
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
                    @endif
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
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
                                            {{ __('Promoção') }}
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                            {{ __('Ativação') }}
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="pl-3 pr-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap sm:pr-6 dark:text-slate-500">
                                            {{ __('Uso') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @forelse($discounts as $discount)
                                        <tr class="relative transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]">
                                            <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                @if(in_array($discount->id, $selected))
                                                    <div class="absolute inset-y-0 left-0 w-0.5 bg-accent-500"></div>
                                                @endif
                                                <x-input
                                                    wire:model="selected"
                                                    wire:key="checkbox-{{ $discount->id }}"
                                                    type="checkbox"
                                                    value="{{ $discount->id }}"
                                                    class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                />
                                            </td>
                                            <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                
                                                <a  href="{{ route('employee.discounts.detail', $discount) }}"
                                                    class="inline-flex items-center truncate font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400"
                                                >
                                                    {{ $discount->code ?? __('Promoção automática #:id', ['id' => $discount->id]) }}
                                                </a>
                                                <p class="font-normal text-slate-500 dark:text-slate-400">
                                                    @if($discount->type === 'percentage')
                                                        {{ $discount->value }}% {{ __('de desconto') }}
                                                    @else
                                                        <x-money :amount="$discount->value" /> {{ __('de desconto') }}
                                                    @endif

                                                    @if($discount->applies_to === 'collections')
                                                        · {{ trans_choice(':total grupo|:total grupos', $discount->collections_count) }}
                                                    @elseif($discount->applies_to === 'products')
                                                        · {{ trans_choice(':total kit|:total kits', $discount->products_count) }}
                                                    @else
                                                        · {{ __('pedido inteiro') }}
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                @if($discount->code)
                                                    <x-badge type="default" size="xs">{{ __('Código') }}</x-badge>
                                                @else
                                                    <x-badge type="primary" size="xs">{{ __('Automático') }}</x-badge>
                                                @endif
                                            </td>
                                            <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                <x-badge :type="$discount->status == 'expired' ? 'default' : ($discount->status == 'active' ? 'success' : 'warning')" size="xs">
                                                    {{ ['active' => __('Ativo'), 'scheduled' => __('Agendado'), 'expired' => __('Expirado')][$discount->status] ?? \Illuminate\Support\Str::ucfirst($discount->status) }}
                                                </x-badge>
                                            </td>
                                            <td class="pl-3 pr-4 py-4 text-sm text-right whitespace-nowrap sm:pr-6 text-slate-500 dark:text-slate-400">
                                                {{ $discount->usage_count }}{{ $discount->usage_limit ? ' / ' . $discount->usage_limit : '' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="px-3 py-12 text-sm text-center whitespace-nowrap" colspan="5">
                                                <div class="max-w-lg mx-auto text-center">
                                                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 dark:bg-white/5">
                                                        <x-heroicon-o-magnifying-glass class="h-6 w-6 text-slate-400" />
                                                    </span>
                                                    <h3 class="mt-3 text-sm font-semibold text-primary dark:text-slate-200">
                                                        {{ __('Nenhum desconto encontrado') }}
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
                </x-slot:content>
            </x-card>

            <div class="mt-6">
                {{ $discounts->links() }}
            </div>

            <x-modal-alert wire:model="showDeleteConfirmationModal">
                <x-slot:title>
                    {{ __('Por favor, confirme sua ação!') }}
                </x-slot:title>
                <x-slot:content>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ trans_choice('Tem certeza de que deseja excluir :count desconto?|Tem certeza de que deseja excluir :count descontos?', count($selected)) }}
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