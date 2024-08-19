<div>
    <form wire:submit.prevent="save">
        <x-card>
            <x-slot:header class="border-b border-slate-200 dark:border-white/10">
                <h3 class="text-base font-semibold leading-6 text-slate-900 dark:text-slate-200">
                    {{ __('Store information') }}
                </h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Provide some basic information to bootstrap your store.') }}
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="storeNameInput"
                            :value="__('Store name')"
                        />
                        <x-input
                            wire:model.defer="state.store_name"
                            type="text"
                            id="storeNameInput"
                            class="mt-1 block w-full sm:text-sm"
                            placeholder="{{ __('My Store') }}"
                        />
                        <x-input-error
                            for="state.store_name"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <x-input-label
                            for="storeSloganInput"
                            :value="__('Slogan')"
                        />
                        <x-input
                            wire:model.defer="state.store_slogan"
                            type="text"
                            id="storeSloganInput"
                            class="mt-1 block w-full sm:text-sm"
                            placeholder="{{ __('Your store slogan') }}"
                        />
                        <x-input-error
                            for="state.store_slogan"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <x-input-label
                            for="storeEmailInput"
                            :value="__('Store email address')"
                        />
                        <x-input
                            wire:model.defer="state.store_contact_email"
                            type="email"
                            id="storeEmailInput"
                            class="mt-1 block w-full sm:text-sm"
                            placeholder="{{ __('contact@yourstore.tld') }}"
                        />
                        <x-input-error
                            for="state.store_contact_email"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <x-input-label
                            for="storeContactPhoneInput"
                            :value="__('Contact phone number')"
                        />
                        <x-input
                            wire:model.defer="state.store_contact_phone"
                            type="text"
                            id="storeContactPhoneInput"
                            class="mt-1 block w-full sm:text-sm"
                            placeholder="{{ __('+1 (555) 123-4567') }}"
                        />
                        <x-input-error
                            for="state.store_contact_phone"
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
