<div>
    <x-slot:title>
        {{ __('Usuários') }} - {{ $employee->name }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ $employee->name }}
                </h1>
            </div>

            <form wire:submit.prevent="save" @disabled(!auth()->user()->is_admin)>
                <div class="space-y-6">
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Perfil') }}</h3>
                        </x-slot:header>
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
                                    <x-input-label for="userPasswordInput" :value="__('Nova senha (opcional)')" />
                                    <div class="relative mt-1">
                                        <x-input
                                            wire:model.defer="state.password"
                                            type="password"
                                            x-bind:type="show ? 'text' : 'password'"
                                            id="userPasswordInput"
                                            class="block w-full rounded-xl pr-10 sm:text-sm"
                                            placeholder="{{ __('Deixe em branco pra manter a atual') }}"
                                        />
                                        <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                            <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                            <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                        </button>
                                    </div>
                                    <x-input-error for="state.password" class="mt-2" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="userWebsiteInput" :value="__('Website (opcional)')" />
                                    <x-input wire:model.defer="state.website" type="text" id="userWebsiteInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                    <x-input-error for="state.website" class="mt-2" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="userBioInput" :value="__('Bio (opcional)')" />
                                    <x-textarea wire:model.defer="state.bio" id="userBioInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                    <x-input-error for="state.bio" class="mt-2" />
                                </div>
                            </div>
                        </x-slot:content>
                    </x-card>

                    @if(auth()->user()->is_admin)
                        <x-card>
                            <x-slot:header>
                                <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Nível de acesso') }}</h3>
                            </x-slot:header>
                            <x-slot:content>
                                @if(auth()->user()->id === $employee->id)
                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        {{ __('Você não pode alterar seu próprio nível de acesso por aqui.') }}
                                    </p>
                                @else
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <x-input wire:model.defer="state.is_admin" type="checkbox" class="mt-0.5 !rounded !shadow-none text-accent-500 focus:ring-accent-500" />
                                        <span>
                                            <span class="block text-sm font-medium text-primary dark:text-slate-200">{{ __('Administrador') }}</span>
                                            <span class="block text-xs text-slate-500 dark:text-slate-400">{{ __('Dá acesso ao Financeiro e a todas as áreas restritas do painel.') }}</span>
                                        </span>
                                    </label>
                                @endif
                            </x-slot:content>
                        </x-card>
                    @endif

                    @if(auth()->user()->is_admin && auth()->user()->id !== $employee->id)
                        <x-card class="!ring-red-100 dark:!ring-red-900/30">
                            <x-slot:header>
                                <h3 class="text-base font-semibold text-red-600 dark:text-red-400">{{ __('Zona de risco') }}</h3>
                            </x-slot:header>
                            <x-slot:content>
                                <div class="divide-y divide-slate-100 dark:divide-white/5">
                                    <div class="pb-5">
                                        @if($employee->isBanned())
                                            <x-input-label :value="__('Restaurar acesso')" />
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                {{ __('O acesso deste funcionário está suspenso no momento.') }}
                                            </p>
                                            <button wire:target="restoreAccess" wire:loading.attr="disabled" wire:click.prevent="restoreAccess" type="button" class="mt-3 btn btn-default !rounded-xl">
                                                {{ __('Restaurar acesso') }}
                                            </button>
                                        @else
                                            <x-input-label :value="__('Suspender acesso')" />
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                {{ __('Esta conta perde o acesso ao painel. Você pode restaurar quando quiser.') }}
                                            </p>
                                            <button wire:target="confirmAccessSuspension" wire:loading.attr="disabled" wire:click.prevent="confirmAccessSuspension" type="button" class="mt-3 btn btn-default !rounded-xl">
                                                {{ __('Suspender acesso') }}
                                            </button>
                                        @endif
                                    </div>
                                    <div class="pt-5">
                                        <x-input-label :value="__('Remover :employeeName', ['employeeName' => $employee->name])" />
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            {{ __('Remove este funcionário definitivamente. Essa ação não pode ser desfeita.') }}
                                        </p>
                                        <button wire:click.prevent="confirmEmployeeRemoval" type="button" class="mt-3 btn btn-danger !rounded-xl">
                                            {{ __('Remover :employeeName', ['employeeName' => $employee->name]) }}
                                        </button>
                                    </div>
                                </div>
                            </x-slot:content>
                        </x-card>
                    @endif

                    <div class="flex justify-end">
                        <a href="{{ route('employee.settings.user.list') }}" class="btn btn-invisible">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="ml-3 btn btn-primary">
                            {{ __('Salvar') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form wire:submit.prevent="suspendAccess">
        <x-modal-alert wire:model="confirmingAccessSuspension">
            <x-slot:title>
                {{ __('Suspender o acesso de :employeeName', ['employeeName' => $employee->name]) }}
            </x-slot:title>
            <x-slot:content>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Tem certeza de que deseja suspender o acesso de :employeeName ao painel?', ['employeeName' => $employee->name]) }}
                </p>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                    {{ __('Suspender') }}
                </button>
                <button x-on:click="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>

    <form wire:submit.prevent="removeEmployee">
        <x-modal-alert wire:model="confirmingEmployeeRemoval">
            <x-slot:title>
                {{ __('Remover :employeeName', ['employeeName' => $employee->name]) }}
            </x-slot:title>
            <x-slot:content>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Tem certeza de que deseja remover :employeeName? Essa ação não pode ser desfeita.', ['employeeName' => $employee->name]) }}
                </p>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-danger w-full sm:ml-3 sm:w-auto">
                    {{ __('Remover') }}
                </button>
                <button x-on:click="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>
</div>