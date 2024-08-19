<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Checkout settings') }}
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
                        {{ __('Checkout') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        {{ __('Customize customer checkout experience.') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div
                            x-data="{ on: @entangle('state.requires_login').defer }"
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
                                    :value="__('Require the customer to log in to their account before checkout')"
                                    class="ml-3"
                                />
                            </div>
                            <x-input-error
                                for="state.requires_login"
                                class="mt-2"
                            />
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
            </div>
        </form>
    </div>
</div>
