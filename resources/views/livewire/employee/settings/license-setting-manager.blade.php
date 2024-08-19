<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('License') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div
            x-data="{ licenseActivated: @entangle('license_active')}"
            class="py-6 xl:flex-auto xl:py-0"
        >
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-200">
                        {{ __('License details') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        Enter random value
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <x-input-label
                                for="licenseKeyInput"
                                :value="__('License key')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="license_key"
                                    type="text"
                                    id="licenseKeyInput"
                                    class="block w-full sm:text-sm"
                                    placeholder="Eg: 123e4567-e89b-12d3-a456-789123456789"
                                    :disabled="$general_settings->license_key"
                                />
                                <x-input-description class="mt-1">
                                    {{ __('Use the license key or purchase code provided to you when you purchased the product to register your store.') }}
                                </x-input-description>
                                <x-input-error
                                    for="license_key"
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
                    x-show="licenseActivated"
                    wire:click="deactivateLicense"
                    type="button"
                    class="btn btn-danger"
                >
                    {{ __('Deactivate') }}
                </button>
                <button
                    x-show="!licenseActivated"
                    wire:target="activateLicense"
                    wire:click.prevent="activateLicense"
                    wire:loading.attr="disabled"
                    type="button"
                    class="btn btn-primary"
                >
                    {{ __('Activate') }}
                </button>
            </div>
        </div>
    </div>
</div>
