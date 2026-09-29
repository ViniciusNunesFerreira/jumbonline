<div>
    <x-slot:title>
        {{ __('Navegação') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                        {{ __('Navegação') }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Menus, ou listas de links, ajudam seus clientes a navegar pela sua loja online.') }}
                    </p>
                </div>
                <div class="mt-4 flex sm:mt-0 sm:ml-4 flex-shrink-0">
                    <button wire:click.prevent="addMenu" type="button" class="btn btn-primary">
                        {{ __('Adicionar novo menu') }}
                    </button>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($menus as $menu)
                    <x-card>
                        <x-slot:header>
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="truncate text-base font-semibold text-primary dark:text-slate-200">
                                        {{ $menu->name }}
                                    </h3>
                                    <p class="font-mono text-xs text-slate-400">{{ $menu->slug }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <x-dropdown>
                                        <x-slot:trigger>
                                            <button type="button" class="group flex items-center rounded-lg p-1 hover:bg-slate-50 dark:hover:bg-white/5">
                                                <span class="sr-only">{{ __('Mais opções') }}</span>
                                                <x-heroicon-o-ellipsis-horizontal class="w-5 h-5 text-slate-500 group-hover:text-primary dark:text-slate-400 dark:group-hover:text-slate-200" />
                                            </button>
                                        </x-slot:trigger>
                                        <x-slot:content>
                                            <x-dropdown-link wire:click.prevent="editMenu({{ $menu->id }})" role="button">
                                                {{ __('Editar') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link wire:click.prevent="confirmMenuDeletion({{ $menu->id }})" role="button" class="!text-red-500">
                                                {{ __('Excluir') }}
                                            </x-dropdown-link>
                                        </x-slot:content>
                                    </x-dropdown>
                                </div>
                            </div>
                        </x-slot:header>
                        <x-slot:content>
                            @if($menu->menuItems->count())
                                <x-menu-tree :items="$menu->menuItems" />
                            @else
                                <button
                                    wire:click.prevent="addMenuItem({{ $menu->id }})"
                                    type="button"
                                    class="group relative block w-full rounded-xl border-2 border-dashed border-slate-200 p-5 text-center hover:border-accent-300 focus:outline-none dark:border-white/10 dark:hover:border-accent-500/50"
                                >
                                    <x-heroicon-o-squares-plus class="mx-auto h-9 w-9 text-slate-300 group-hover:text-accent-500 dark:text-slate-600" />
                                    <span class="mt-2 block text-sm font-semibold text-accent-600 dark:text-accent-400">
                                        {{ __('Adicionar o primeiro item a este menu') }}
                                    </span>
                                </button>
                            @endif
                        </x-slot:content>
                    </x-card>
                @empty
                    <x-card>
                        <x-slot:content>
                            <div class="max-w-lg mx-auto text-center py-6">
                                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                    <x-heroicon-o-bars-3-bottom-left class="h-7 w-7 text-accent-500" />
                                </span>
                                <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                                    {{ __('Nenhum menu criado') }}
                                </h3>
                                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Crie um menu e depois escolha onde ele aparece na tela de Layout.') }}
                                </p>
                                <div class="mt-6">
                                    <button wire:click.prevent="addMenu" type="button" class="btn btn-primary">
                                        <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                        {{ __('Adicionar novo menu') }}
                                    </button>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-card>
                @endforelse
            </div>
        </div>
    </div>

    <form wire:submit.prevent="saveMenu">
        <x-slide-over wire:model="showMenuForm">
            <x-slot:title>
                {{ optional($menu)->exists ? __('Editar menu') : __('Adicionar menu') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label for="menuNameInput" value="{{ __('Título') }}" />
                        <x-input wire:model.defer="menu.name" type="text" id="menuNameInput" class="mt-1 block w-full rounded-xl sm:text-sm" placeholder="{{ __('Ex.: Menu do rodapé') }}" />
                        <x-input-error for="menu.name" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="menuHandleInput" value="{{ __('Identificador (handle)') }}" />
                        <x-input wire:model.defer="menu.slug" type="text" id="menuHandleInput" class="mt-1 block w-full rounded-xl sm:text-sm" placeholder="{{ __('Ex.: menu-rodape') }}" />
                        <p class="mt-1 text-xs text-slate-400">
                            {{ __('O identificador é usado para referenciar o menu na tela de Layout.') }}
                        </p>
                        <x-input-error for="menu.slug" class="mt-1" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <div class="flex flex-shrink-0 justify-end">
                    <button x-on:click="show = false" type="button" class="btn btn-invisible">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="ml-4 btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-slide-over>
    </form>

    <form wire:submit.prevent="deleteMenu">
        <x-modal-alert wire:model="confirmingMenuDeletion">
            <x-slot:title>
                {{ __('Remover menu?') }}
            </x-slot:title>
            <x-slot:content>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Tem certeza de que deseja excluir este menu? Essa ação não pode ser desfeita!') }}
                </p>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-danger w-full sm:ml-3 sm:w-auto">
                    {{ __('Excluir') }}
                </button>
                <button x-on:click="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>

    <form wire:submit.prevent="saveMenuItem">
        <x-slide-over wire:model="showMenuItemForm">
            <x-slot:title>
                {{ optional($menuItem)->exists ? __('Editar item') : __('Adicionar item') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label for="menuItemNameInput" value="{{ __('Nome') }}" />
                        <x-input wire:model.defer="menuItem.name" type="text" id="menuItemNameInput" class="mt-1 block w-full rounded-xl sm:text-sm" placeholder="{{ __('Ex.: Fale conosco') }}" autofocus />
                        <x-input-error for="menuItem.name" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="menuItemUrlInput" value="{{ __('Link') }}" />
                        <x-input wire:model.defer="menuItem.url" type="text" id="menuItemUrlInput" class="mt-1 block w-full rounded-xl sm:text-sm" placeholder="{{ __('Ex.: https://exemplo.com.br') }}" />
                        <x-input-error for="menuItem.url" class="mt-1" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <div class="flex flex-shrink-0 justify-end">
                    <button x-on:click="show = false" type="button" class="btn btn-invisible">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="ml-4 btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-slide-over>
    </form>

    <form wire:submit.prevent="deleteMenuItem">
        <x-modal-alert wire:model="confirmingMenuItemDeletion">
            <x-slot:title>
                {{ __('Remover item do menu?') }}
            </x-slot:title>
            <x-slot:content>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Tem certeza de que deseja excluir este item do menu? Essa ação não pode ser desfeita!') }}
                </p>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-danger w-full sm:ml-3 sm:w-auto">
                    {{ __('Excluir') }}
                </button>
                <button x-on:click="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>
</div>