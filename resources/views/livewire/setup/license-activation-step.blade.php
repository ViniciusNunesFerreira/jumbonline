<div>
    <form wire:submit.prevent="save">
        <x-card>
            <x-slot:header class="border-b border-slate-200 dark:border-white/10">
                <h3 class="text-base font-semibold leading-6 text-slate-900 dark:text-slate-200">
                    {{ __('License activation') }}
                </h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Enter random value
                </p>
            </x-slot:header>

            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="licenseKeyInput"
                            :value="__('License key')"
                        />
                        <x-input
                            wire:model.defer="state.license_key"
                            type="text"
                            id="licenseKeyInput"
                            class="mt-1 block w-full sm:text-sm"
                            placeholder="Eg: 123e4567-e89b-12d3-a456-789123456789"
                        />
                        <x-input-error
                            for="state.license_key"
                            class="mt-2"
                        />
                    </div>
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div class="flex flex-col items-center space-y-3">
                    <button
                        type="submit"
                        class="btn btn-primary block w-full"
                    >
                        {{ __('Activate and continue') }}
                    </button>

                    <button
                        wire:click.prevent="skip"
                        type="button"
                        class="btn btn-link"
                    >
                        {{ __('Skip for now, you may do this later') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-card>
    </form>
</div>
