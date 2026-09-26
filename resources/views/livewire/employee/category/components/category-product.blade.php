<div>
    <x-card class="relative overflow-hidden">
        <x-slot:header>
            <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                    {{ __('Produtos') }}
                </h3>
                @if(count($category->products))
                    <button wire:click.prevent="browse" type="button" class="btn btn-link">
                        {{ __('Adicionar') }}
                    </button>
                @endif
            </div>
        </x-slot:header>
        <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
            <ul class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($category->products as $product)
                    <li class="flex items-center justify-between px-4 py-3.5 sm:px-6 transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]">
                        <div class="flex items-center min-w-0">
                            <img
                                src="{{ $product->getFirstMediaUrl('gallery', 'thumb_large') }}"
                                alt="{{ $product->name }}"
                                class="rounded-lg object-center object-cover w-10 h-10 ring-1 ring-slate-100 dark:ring-white/10 flex-shrink-0"
                            >
                            
                            <a  href="{{ route('employee.products.detail', $product) }}"
                                class="ml-3.5 truncate font-medium text-sm text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400"
                            >
                                {{ $product->name }}
                            </a>
                        </div>
                        <button
                            wire:click.prevent="delete('{{ $product->slug }}')"
                            type="button"
                            class="text-slate-400 hover:text-red-500 flex-shrink-0"
                        >
                            <span class="sr-only">{{ __('Remover produto') }}</span>
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                        </button>
                    </li>
                @empty
                    <li class="py-8 text-center">
                        <x-heroicon-o-folder-open class="mx-auto h-10 w-10 text-slate-300" />
                        <h3 class="mt-2 text-sm font-semibold text-primary dark:text-slate-200">
                            {{ __('Nenhum produto') }}
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Não há produtos nesta categoria') }}
                        </p>
                        <div class="mt-4">
                            <button wire:click.prevent="browse" type="button" class="btn btn-primary">
                                <x-heroicon-s-plus class="-ml-1 mr-2 h-5 w-5" />
                                {{ __('Explorar produtos') }}
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
            <div x-init="$watch('show', value => value && $wire.loadProducts())" class="-mx-4 sm:-mx-6">
                <div class="p-4 border-t border-slate-100 sm:px-6 dark:border-white/5">
                    <div class="max-w-none group relative rounded-xl">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-slate-400 group-focus-within:text-primary dark:group-focus-within:text-slate-300" />
                        </div>
                        <x-input
                            wire:model.debounce.500ms="search"
                            type="text"
                            class="block w-full rounded-xl px-10 sm:text-sm"
                            :placeholder="__('Buscar produtos')"
                            autofocus
                        />
                        <div
                            wire:target="search"
                            wire:loading.flex
                            wire:loading.class.remove="hidden"
                            class="pointer-events-none hidden absolute inset-y-0 right-0 items-center pr-3"
                        >
                            <x-heroicon-m-arrow-path class="animate-spin h-5 w-5 text-slate-400" />
                        </div>
                    </div>
                </div>
                <div
                    wire:target="loadProducts, search"
                    wire:loading
                    class="p-4 bg-white w-full border-y border-slate-100 sm:px-6 dark:bg-slate-900 dark:border-white/5"
                >
                    <div class="flex space-x-4 animate-pulse">
                        <div class="rounded-full bg-slate-100 dark:bg-white/10 h-10 w-10"></div>
                        <div class="flex-1 space-y-6 py-1">
                            <div class="h-2 bg-slate-100 dark:bg-white/10 rounded"></div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="h-2 bg-slate-100 dark:bg-white/10 rounded col-span-2"></div>
                                    <div class="h-2 bg-slate-100 dark:bg-white/10 rounded col-span-1"></div>
                                </div>
                                <div class="h-2 bg-slate-100 dark:bg-white/10 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div wire:target="loadProducts, search" wire:loading.remove class="relative border-y border-slate-100 dark:border-white/5">
                    <ul class="overflow-y-auto divide-y divide-slate-100 max-h-[30rem] sm:max-h-[40rem] dark:divide-white/5">
                        @forelse($products as $product)
                            <li class="relative p-4 sm:px-6 hover:bg-slate-50/80 dark:hover:bg-white/[0.03]">
                                <div class="flex items-center">
                                    <x-input-label for="product-{{ $product->id }}" class="absolute inset-0 cursor-pointer" />
                                    <x-input
                                        wire:model.defer="selected"
                                        id="product-{{ $product->id }}"
                                        type="checkbox"
                                        class="mr-3 !rounded !shadow-none text-accent-500 focus:ring-accent-500"
                                        value="{{ $product->id }}"
                                    />
                                    <img
                                        src="{{ $product->getFirstMediaUrl('gallery', 'thumb') }}"
                                        alt="{{ $product->name }}"
                                        class="rounded-lg w-10 h-10"
                                    >
                                    <span class="ml-3 font-medium line-clamp-2 text-sm text-primary dark:text-slate-200">
                                        {{ $product->name }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Nenhum produto encontrado.') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click.prevent="save" wire:loading.attr="disabled" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                {{ __('Feito') }}
            </button>
            <button x-on:click="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-dialog>
</div>