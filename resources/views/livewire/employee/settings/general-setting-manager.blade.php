<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('General settings') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <form
            wire:submit.prevent="save"
            class="py-6 xl:flex-auto xl:py-0"
        >
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-200">
                        {{ __('Store details') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        {{ __('View and update your store details.') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <x-input-label
                                for="storeNameInput"
                                :value="__('Store name')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.store_name"
                                    type="text"
                                    id="storeNameInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.store_name"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="contactEmailInput"
                                :value="__('Contact email')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.contact_email"
                                    type="text"
                                    id="contactEmailInput"
                                    class="block w-full sm:text-sm"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="contactPhoneInput"
                                :value="__('Contact phone')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.contact_phone"
                                    type="text"
                                    id="contactPhoneInput"
                                    class="block w-full sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-gray-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-200">
                        {{ __('Cookie consent') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        {{ __('Configure your cookie consent banner.') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div
                            x-data="{ on: @entangle('state.cookie_consent_enabled').defer }"
                            class="col-span-full"
                        >
                            <div class="flex items-center">
                                <button
                                    x-on:click="on = !on"
                                    x-ref="switch"
                                    type="button"
                                    role="switch"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                                    :class="{ 'bg-sky-500': on, 'bg-gray-200 dark:bg-gray-700': !(on) }"
                                    :aria-checked="on.toString()"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"
                                    ></span>
                                </button>
                                <x-input-label
                                    x-on:click="on = !on; $refs.switch.focus()"
                                    :value="__('Enable')"
                                    class="ml-3"
                                />
                            </div>
                            <x-input-error
                                for="state.cookie_consent_enabled"
                                class="mt-2"
                            />
                        </div>
                        <div class="col-span-full">
                            <x-input-label
                                for="cookieConsentMessageInput"
                                :value="__('Message')"
                            />
                            <div class="mt-2">
                                <x-textarea
                                    wire:model.defer="state.cookie_consent_message"
                                    id="cookieConsentMessageInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.cookie_consent_message"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="cookieConsentAgreeInput"
                                :value="__('Agree button text')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.cookie_consent_agree"
                                    type="text"
                                    id="cookieConsentAgreeInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.cookie_consent_agree"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="cookieConsentRejectInput"
                                :value="__('Reject button text')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.cookie_consent_reject"
                                    type="text"
                                    id="cookieConsentRejectInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.cookie_consent_reject"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button
                    type="button"
                    class="btn btn-default"
                >
                    {{ __('Cancel') }}
                </button>
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
