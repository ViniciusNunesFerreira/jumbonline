<div>
    <x-slot:title>
        {{ __('Unidades Prisionais') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Unidades Prisionais') }}
                </h1>
            </div>
            @if($prison_units->count())
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    
                    <a  href="{{ route('employee.prison.create') }}"
                        class="btn btn-primary block w-full order-0 sm:order-1 sm:ml-3"
                    >
                        {{ __('Nova Unidade Prisional') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="mt-6">
            @if(!$prison_units->count() && !$search)
                <x-card>
                    <x-slot:content>
                        <div class="max-w-lg mx-auto text-center py-6">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                <x-heroicon-o-building-office class="h-7 w-7 text-accent-500" />
                            </span>

                            <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Todas as Unidades Prisionais Aqui') }}
                            </h3>

                            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Todas as unidades prisionais serão cadastradas, incluindo as categorias e endereços') }}
                            </p>

                            <div class="mt-6">
                                <a href="{{ route('employee.prison.create') }}" class="btn btn-primary">
                                    <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                    {{ __('Nova Unidade Prisional') }}
                                </a>
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
                                placeholder="{{ __('Filtrar unidades') }}"
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
                                        <div wire:loading.flex class="h-full w-screen items-center justify-center sm:w-auto">
                                            <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Buscando unidades...') }}</p>
                                        </div>
                                    </div>
                                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                                        <thead>
                                            <tr class="border-b border-slate-100 dark:border-white/5">
                                                <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Nome') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Cidade') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('CEP') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Categoria') }}
                                                </th>
                                                <th scope="col" class="pl-3 pr-6 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Grupos') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @forelse($prison_units as $prison)
                                                <tr
                                                    wire:loading.class.delay="opacity-50"
                                                    class="transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]"
                                                >
                                                    <td class="px-6 py-4 font-medium text-sm text-left whitespace-nowrap">
                                                        
                                                        <a  href="{{ route('employee.prison.detail', $prison->id) }}"
                                                            class="inline-flex items-center truncate font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400"
                                                        >
                                                            {{ $prison->name }}
                                                        </a>
                                                    </td>
                                                    <td class="px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                        {{ $prison->cidade }}
                                                    </td>
                                                    <td class="px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                        {{ $prison->cep }}
                                                    </td>
                                                    <td class="px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        <x-badge type="default" size="xs">{{ $prison->prisonCategory->name }}</x-badge>
                                                    </td>
                                                    <td class="pl-3 pr-6 py-4 text-right text-sm text-slate-500 whitespace-nowrap dark:text-slate-400">
                                                        {{ trans_choice(':count grupo|:count grupos', $prison->collections()->count()) }}
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
                                                                {{ __('Sem unidades cadastradas') }}
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
                    {{ $prison_units->links() }}
                </div>
            @endif
        </div>
    </div>
</div>