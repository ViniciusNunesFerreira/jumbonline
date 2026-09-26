<div>
    <x-slot:title>
        {{ __('Usuários') }} - {{ $state['is_admin'] ? __('Novo administrador') : __('Novo funcionário') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ $state['is_admin'] ? __('Novo administrador') : __('Novo funcionário') }}
                </h1>
            </div>

            <form wire:submit.prevent="save">
                <x-card>
                    <x-slot:content>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <x-input-label for="userNameInput" :value="__('Nome completo')" />
                                <x-input wire:model.defer="state.name" type="text" id="userNameInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.name" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="userEmailInput" :value="__('E-mail')" />
                                <x-input wire:model.defer="state.email" type="text" id="userEmailInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.email" class="mt-2" />
                            </div>
                            <div x-data="{ show: false }">
                                <x-input-label for="userPasswordInput" :value="__('Senha')" />
                                <div class="relative mt-1">
                                    <x-input
                                        wire:model.defer="state.password"
                                        type="password"
                                        x-bind:type="show ? 'text' : 'password'"
                                        id="userPasswordInput"
                                        class="block w-full rounded-xl pr-10 sm:text-sm"
                                    />
                                    <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                        <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                                <x-input-error for="state.password" class="mt-2" />
                            </div>
                        </div>

                        @if($state['is_admin'])
                            <div class="mt-5 rounded-xl border border-accent-200 bg-accent-50 p-3 text-xs text-accent-700 dark:border-accent-500/20 dark:bg-accent-500/10 dark:text-accent-400">
                                {{ __('Este usuário terá acesso ao Financeiro e a todas as áreas restritas do painel.') }}
                            </div>
                        @endif
                    </x-slot:content>
                    <x-slot:footer>
                        <div class="flex justify-end">
                            <a href="{{ route('employee.settings.user.list') }}" class="btn btn-invisible">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="ml-3 btn btn-primary">
                                {{ __('Salvar') }}
                            </button>
                        </div>
                    </x-slot:footer>
                </x-card>
            </form>
        </div>
    </div>
</div>