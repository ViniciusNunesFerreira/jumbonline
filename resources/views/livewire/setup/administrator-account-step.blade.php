<div>
    <div>
        <form wire:submit.prevent="save">
            <x-card>
                <x-slot:header class="border-b border-slate-200 dark:border-white/10">
                    <h3 class="text-base font-semibold leading-6 text-slate-900 dark:text-slate-200">
                        {{ __('Administrator account') }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Create an administrator account to manage your store.') }}
                    </p>
                </x-slot:header>

                <x-slot:content>
                    <div class="space-y-6">
                        <div>
                            <x-input-label
                                for="adminNameInput"
                                :value="__('Name')"
                            />
                            <x-input
                                wire:model.defer="state.administrator_name"
                                type="text"
                                id="adminNameInput"
                                class="mt-1 block w-full sm:text-sm"
                                placeholder="{{ __('John Doe') }}"
                            />
                            <x-input-error
                                for="state.administrator_name"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="adminEmailInput"
                                :value="__('Email address')"
                            />
                            <x-input
                                wire:model.defer="state.administrator_email"
                                type="email"
                                id="adminEmailInput"
                                class="mt-1 block w-full sm:text-sm"
                                placeholder="{{ __('john@example.org') }}"
                            />
                            <x-input-error
                                for="state.administrator_email"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="adminPasswordInput"
                                :value="__('Password')"
                            />
                            <x-input
                                wire:model.defer="state.administrator_password"
                                type="password"
                                id="adminPasswordInput"
                                class="mt-1 block w-full sm:text-sm"
                                placeholder="{{ __('********') }}"
                            />
                            <x-input-error
                                for="state.administrator_password"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="adminPasswordConfirmationInput"
                                :value="__('Password confirmation')"
                            />
                            <x-input
                                wire:model.defer="state.administrator_password_confirmation"
                                type="password"
                                id="adminPasswordConfirmationInput"
                                class="mt-1 block w-full sm:text-sm"
                                placeholder="{{ __('********') }}"
                            />
                            <x-input-error
                                for="state.administrator_password_confirmation"
                                class="mt-2"
                            />
                        </div>
                    </div>
                </x-slot:content>

                <x-slot:footer>
                    <button
                        type="submit"
                        class="btn btn-primary block w-full"
                    >
                        {{ __('Save and continue') }}
                    </button>
                </x-slot:footer>
            </x-card>
        </form>
    </div>

</div>
