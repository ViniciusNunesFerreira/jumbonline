<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Users') }} - {{ $employee->name }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')
        <form
            @disabled(!auth()->user()->is_admin)
            wire:submit.prevent="save"
            class="py-6 xl:flex-auto xl:py-0"
        >
            <div class="space-y-12">
                <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                        {{ __('Store profile') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        {{ __('Manage :employeeName’s profile.', ['employeeName' => $employee->name]) }}
                    </p>
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
                                :value="__('Password (optional)')"
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
                        <div class="sm:col-span-5">
                            <x-input-label
                                for="userWebsiteInput"
                                :value="__('Website (optional)')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.website"
                                    type="text"
                                    id="userWebsiteInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.website"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="col-span-full">
                            <x-input-label
                                for="userBioInput"
                                :value="__('Bio (optional)')"
                            />
                            <div class="mt-2">
                                <x-textarea
                                    wire:model.defer="state.bio"
                                    id="userBioInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.bio"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->is_admin && auth()->user()->id !== $employee->id)
                    <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                        <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                            {{ __('Manage staff access') }}
                        </h2>
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 divide-y divide-slate-200 sm:grid-cols-6">
                            <div class="col-span-full">
                                <div class="mt-2">
                                    @if($employee->isBanned())
                                        <x-input-label :value="__('Restore access')" />
                                        <p class="mt-1 text-sm leading-6 text-slate-500">
                                            {{ __('This staff member\'s access is currently suspended.') }}
                                        </p>
                                        <button
                                            wire:target="restoreAccess"
                                            wire:loading.attr="disabled"
                                            wire:click.prevent="restoreAccess"
                                            class="mt-2 btn btn-default"
                                        >
                                            {{ __('Restore access') }}
                                        </button>
                                    @else
                                        <x-input-label :value="__('Suspend access')" />
                                        <p class="mt-1 text-sm leading-6 text-slate-500">
                                            {{ __('This account will no longer have access to your store. You can restore access at any time.') }}
                                        </p>
                                        <button
                                            wire:target="confirmAccessSuspension"
                                            wire:loading.attr="disabled"
                                            wire:click.prevent="confirmAccessSuspension"
                                            class="mt-2 btn btn-default"
                                        >
                                            {{ __('Suspend access') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="pt-8 col-span-full">
                                <x-input-label :value="__('Remove :employeeName', ['employeeName' => $employee->name])" />
                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    {{ __('Removed staff members will be permanently removed from your store. This action cannot be reversed.') }}
                                </p>
                                <div class="mt-2">
                                    <button
                                        wire:click.prevent="confirmEmployeeRemoval"
                                        class="btn btn-danger"
                                    >
                                        {{ __('Remove :employeeName', ['employeeName' => $employee->name]) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
            </div>
        </form>
    </div>

    <form wire:submit.prevent="suspendAccess">
        <x-modal-alert wire:model="confirmingAccessSuspension">
            <x-slot:title>
                {{ __('Suspend :employeeName\'s account access', ['employeeName' => $employee->name]) }}
            </x-slot:title>
            <x-slot:content>
                <p class="text-sm leading-5 text-slate-500">
                    {{ __('Are you sure you want to suspend :employeeName\'s access to your store?', ['employeeName' => $employee->name]) }}
                </p>
            </x-slot:content>
            <x-slot:footer>
                <button
                    type="submit"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Suspend') }}
                </button>
                <button
                    x-on:click="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>

    <form wire:submit.prevent="removeEmployee">
        <x-modal-alert wire:model="confirmingEmployeeRemoval">
            <x-slot:title>
                {{ __('Remove :employeeName', ['employeeName' => $employee->name]) }}
            </x-slot:title>
            <x-slot:content>
                <p class="text-sm leading-5 text-slate-500">
                    {{ __('Are you sure you want to remove :employeeName from your store? This action cannot be reversed.', ['employeeName' => $employee->name]) }}
                </p>
            </x-slot:content>
            <x-slot:footer>
                <button
                    type="submit"
                    class="btn btn-danger w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Remove') }}
                </button>
                <button
                    x-on:click="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>
</div>
