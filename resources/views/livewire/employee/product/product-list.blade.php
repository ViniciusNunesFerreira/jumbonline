<div>
    <x-slot:title>
        {{ __('Produtos') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Produtos') }}
                </h1>
            </div>
            @if($products->count())
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    <button
                        wire:click.prevent="newProduct"
                        class="btn btn-primary block w-full order-0 sm:order-1 sm:ml-3"
                    >
                        {{ __('Add produto') }}
                    </button>
                </div>
            @endif
        </div>

        <div class="mt-6">
            @if(!$products->count() && !$search)
                <x-card>
                    <x-slot:content>
                        <div class="max-w-lg mx-auto text-center py-6">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                <x-heroicon-o-tag class="h-7 w-7 text-accent-500" />
                            </span>

                            <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Primeiro: o que você está vendendo?') }}
                            </h3>

                            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Antes de abrir sua loja, primeiro você precisa de alguns produtos.') }}
                            </p>

                            <div class="mt-6">
                                <button
                                    wire:click.prevent="newProduct"
                                    type="button"
                                    class="btn btn-primary"
                                >
                                    <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                    {{ __('Add seus produtos') }}
                                </button>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            @else
                <x-card class="overflow-hidden">
                    <x-slot:header>
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
                                placeholder="{{ __('Filtrar produtos') }}"
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
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="relative overflow-hidden">
                                    <div
                                        wire:loading.delay
                                        class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[1px] dark:bg-slate-900/60"
                                    >
                                        <div
                                            wire:loading.flex
                                            class="h-full w-screen items-center justify-center sm:w-auto"
                                        >
                                            <div class="m-auto flex items-center space-x-2">
                                                <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Carregando produtos...') }}</p>
                                            </div>
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
                                                    {{ __('Produto') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Valor') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Peso') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Categoria') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Status') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @forelse($products as $product)
                                                <tr
                                                    wire:loading.class.delay="opacity-50"
                                                    class="relative transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]"
                                                >
                                                    <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                        @if(in_array($product->id, $selected))
                                                            <div class="absolute inset-y-0 left-0 w-0.5 bg-accent-500"></div>
                                                        @endif
                                                        <x-input
                                                            wire:model="selected"
                                                            wire:key="checkbox-{{ $product->id }}"
                                                            type="checkbox"
                                                            value="{{ $product->id }}"
                                                            class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                        />
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 flex-shrink-0">
                                                                <img
                                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10"
                                                                    src="{{ $product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                                    alt="{{ $product->name }}"
                                                                >
                                                            </div>
                                                            <div class="ml-3.5">
                                                                
                                                                <a    href="{{ route('employee.products.detail', $product->id) }}"
                                                                    class="inline-flex items-center truncate font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400"
                                                                >
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-4 text-left text-sm font-semibold text-primary whitespace-nowrap dark:text-slate-200">
                                                        <x-money
                                                            :amount="optional($product->first_variant)->price"
                                                            :currency="config('app.currency')"
                                                        />
                                                    </td>
                                                    <td class="px-3 py-4 text-left text-sm text-slate-500 whitespace-nowrap dark:text-slate-400">
                                                        {{ optional($product->first_variant)->weight_value }}{{ optional($product->first_variant)->weight_unit }}
                                                    </td>
                                                    <td class="px-3 py-4 text-left text-sm whitespace-nowrap">
                                                        @forelse($product->categories as $category)
                                                            <x-badge type="default" size="xs" class="mr-1">
                                                                {{ $category->title }} · {{ __('máx.') }} {{ $category->quantity }}
                                                            </x-badge>
                                                        @empty
                                                            <span class="text-slate-400">{{ __('Sem categoria') }}</span>
                                                        @endforelse
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-center whitespace-nowrap">
                                                        <x-badge :type="$product->is_active ? 'success' : 'default'" size="xs">
                                                            {{ $product->status->label() }}
                                                        </x-badge>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="px-3 py-12 text-sm text-center whitespace-nowrap" colspan="6">
                                                        <div class="max-w-lg mx-auto text-center">
                                                            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 dark:bg-white/5">
                                                                <x-heroicon-o-magnifying-glass class="h-6 w-6 text-slate-400" />
                                                            </span>
                                                            <h3 class="mt-3 text-sm font-semibold text-primary dark:text-slate-200">
                                                                {{ __('Nenhum produto encontrado') }}
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
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>