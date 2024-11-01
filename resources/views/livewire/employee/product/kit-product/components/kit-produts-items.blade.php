<div>
    <x-card class="relative overflow-hidden">
        <x-slot:header>
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                <div class="ml-4 mt-2">
                    <h3 class="text-base leading-6 font-medium text-slate-900 dark:text-slate-200">
                        {{ __('Produtos') }}
                    </h3>
                </div>
                @if(count($kit->products))
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <button
                            wire:click.prevent="browse"
                            class="btn btn-link"
                        >
                            {{ __('Adicionar') }}
                        </button>
                    </div>
                @endif
            </div>
        </x-slot:header>
        <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
            <ul class="border-t border-slate-300 divide-y divide-slate-200 dark:border-slate-200/20 dark:divide-slate-700/50">
                @forelse($kit->products as $product)
                    <li class="p-4 sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-800/75">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img
                                    src="{{ $product->getFirstMediaUrl('gallery', 'thumb_large') }}"
                                    alt="{{ $product->name }}"
                                    class="rounded object-center object-cover w-10 h-10"
                                >

                                    {{ $product->name }}

                            </div>
                            <div class="pl-5 flex items-center">
                                <button
                                    wire:click.prevent="delete('{{ $product->slug }}')"
                                    class="text-slate-500 hover:text-slate-600 dark:hover:text-slate-400"
                                >
                                    <span class="sr-only">{{ __('Deletar produto') }}</span>
                                    <x-heroicon-o-x-mark class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-center">
                        <x-heroicon-o-folder-open class="mx-auto h-12 w-12 text-slate-400" />
                        <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Nenhum produto') }}
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Não há produtos nesta categoria') }}
                        </p>
                        <div class="mt-6">
                            <button
                                wire:click.prevent="browse"
                                class="btn btn-primary"
                            >
                                <x-heroicon-s-plus class="-ml-1 mr-2 h-5 w-5" />
                                {{ __('Explorar Produtos') }}
                            </button>
                        </div>
                    </li>
                @endforelse
            </ul>
        </x-slot:content>
    </x-card>


    <x-modal-dialog wire:model.defer="isBrowsingProducts">
        <x-slot:title>
            {{ __('Editar Produtos') }}
        </x-slot:title>
        <x-slot:content>
            <div
                x-init="$watch('show', value => value && $wire.loadProducts())"
                class="-mx-4 sm:-mx-6"
            >
                <div class="p-4 border-t border-slate-300 sm:px-6 dark:border-slate-700">
                    <div class="max-w-none group relative rounded-md">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-slate-400 group-focus-within:text-slate-500 dark:group-focus-within:text-slate-300" />
                        </div>
                        <x-input
                            wire:model.debounce.500ms="search"
                            type="text"
                            class="block w-full px-10 sm:text-sm"
                            :placeholder="__('Buscar Produtos')"
                            autofocus
                        />
                        <div
                            wire:target="search"
                            wire:loading.flex
                            wire:loading.class.remove="hidden"
                            class="pointer-events-none hidden absolute inset-y-0 right-0 items-center pr-3"
                        >
                            <x-heroicon-m-arrow-path class="animate-spin h-5 w-5 text-gray-400" />
                        </div>
                    </div>
                </div>
                <div
                    wire:target="loadProducts, search"
                    wire:loading
                    class="p-4 bg-white w-full border-y border-slate-300 sm:px-6 dark:bg-slate-800 dark:border-slate-700"
                >
                    <div class="flex space-x-4 animate-pulse">
                        <div class="rounded-full bg-slate-200 dark:bg-slate-700 h-10 w-10"></div>
                        <div class="flex-1 space-y-6 py-1">
                            <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded"></div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded col-span-2"></div>
                                    <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded col-span-1"></div>
                                </div>
                                <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    wire:target="loadProducts, search"
                    wire:loading.remove
                    class="relative border-y border-slate-300 dark:border-slate-700"
                >
                    <ul class="overflow-y-auto divide-y divide-slate-200 max-h-[30rem] sm:max-h-[40rem] dark:divide-slate-700/50">
                        @forelse($products as $product)
                            <div class="flex flex-row justify-between items-center px-4">
                                <li class="relative p-4 sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                    <div class="flex items-center">
                                        <x-input-label
                                            for="product-{{ $product->id }}"
                                            class="absolute inset-0 cursor-pointer"
                                        />
                                        <x-input
                                            wire:model.defer="selected"
                                            id="product-{{ $product->id }}"
                                            type="checkbox"
                                            class="mr-3 !rounded !shadow-none"
                                            value="{{ $product->id }}"
                                        />
                                        <img
                                            src="{{ $product->getFirstMediaUrl('gallery', 'thumb') }}"
                                            alt="{{ $product->name }}"
                                            class="rounded-md w-10 h-10"
                                        >
                                        <span class="ml-3 font-medium line-clamp-2 text-sm">
                                            {{ $product->name }}
                                        </span>
                                    </div>
                                
                                </li>

                                <input  type="number" min="1"  wire:model.defer="product_list.{{$product->id}}" class="input-number bg-gray-50  border-gray-300 h-8 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block py-2.5  " required />
                            </div>
                        @empty
                            <li class="py-4 text-center text-sm text-slate-700 dark:text-slate-400">
                                {{ __('No products found.') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button
                wire:click.prevent="save"
                wire:loading.attr="disabled"
                class="btn btn-primary w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Feito') }}
            </button>
            <button
                x-on:click="show = false"
                class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
            >
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-dialog>
</div>
