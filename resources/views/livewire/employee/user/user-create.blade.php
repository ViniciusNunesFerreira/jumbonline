<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Users') }} - {{ $state['is_admin'] ? __('Add admin') : __('Add staff') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <form
            wire:submit.prevent="save"
            class="py-6 xl:flex-auto xl:py-0"
        >
            <div class="space-y-12">
                <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                        {{ $state['is_admin'] ? __('Add admin') : __('Add staff') }}
                    </h2>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <x-input-label
                                for="userNameInput"
                                :value="__('Full name')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.name"
                                    type="text"
                                    id="userNameInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.name"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="userEmailInput"
                                :value="__('Email address')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.email"
                                    type="text"
                                    id="userEmailInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.email"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="userPasswordInput"
                                :value="__('Password')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.password"
                                    type="password"
                                    id="userPasswordInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.password"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a
                    href="{{ route('employee.settings.user.list') }}"
                    class="btn btn-default"
                >
                    {{ __('Cancel') }}
                </a>
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    {{ __('Save changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
