<div>
    <form wire:submit.prevent="save">
        <x-card>
            <x-slot:header>
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Alterar senha') }}</h3>
            </x-slot:header>
            <x-slot:content>
                <div class="space-y-5 max-w-sm">
                    <div x-data="{ show: false }">
                        <x-input-label for="currentPasswordInput" :value="__('Senha atual')" />
                        <div class="relative mt-1">
                            <x-input
                                wire:model.defer="state.current_password"
                                type="password"
                                x-bind:type="show ? 'text' : 'password'"
                                id="currentPasswordInput"
                                class="block w-full rounded-xl pr-10 sm:text-sm"
                            />
                            <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                        <x-input-error for="state.current_password" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <x-input-label for="newPasswordInput" :value="__('Nova senha')" />
                        <div class="relative mt-1">
                            <x-input
                                wire:model.defer="state.password"
                                type="password"
                                x-bind:type="show ? 'text' : 'password'"
                                id="newPasswordInput"
                                class="block w-full rounded-xl pr-10 sm:text-sm"
                            />
                            <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                        <x-input-error for="state.password" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <x-input-label for="confirmNewPasswordInput" :value="__('Confirmar nova senha')" />
                        <div class="relative mt-1">
                            <x-input
                                wire:model.defer="state.password_confirmation"
                                type="password"
                                x-bind:type="show ? 'text' : 'password'"
                                id="confirmNewPasswordInput"
                                class="block w-full rounded-xl pr-10 sm:text-sm"
                            />
                            <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                        <x-input-error for="state.password_confirmation" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-card>
    </form>
</div>