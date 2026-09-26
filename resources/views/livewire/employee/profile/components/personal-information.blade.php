<div>
    <form wire:submit.prevent="save">
        <x-card>
            <x-slot:header>
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Perfil') }}</h3>
            </x-slot:header>
            <x-slot:content>
                <div class="flex items-center gap-x-6">
                    @if($avatarFile)
                        <img src="{{ $avatarFile->temporaryUrl() }}" alt="" class="h-20 w-20 flex-none rounded-full bg-slate-100 object-cover ring-2 ring-white dark:bg-slate-800 dark:ring-slate-900">
                    @else
                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}" alt="" class="h-20 w-20 flex-none rounded-full bg-slate-100 object-cover ring-2 ring-white dark:bg-slate-800 dark:ring-slate-900">
                    @endif
                    <div x-data>
                        <x-input wire:model="avatarFile" x-ref="avatarInput" type="file" class="sr-only" />
                        <button x-on:click="$refs.avatarInput.click()" type="button" class="btn btn-default !rounded-xl">
                            {{ __('Alterar avatar') }}
                        </button>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('JPG, GIF ou PNG. Máximo 1MB.') }}
                        </p>
                    </div>
                </div>
                <x-input-error for="avatarFile" class="mt-2" />

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="nameInput" :value="__('Nome')" />
                        <x-input wire:model.defer="state.name" type="text" id="nameInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                        <x-input-error for="state.name" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="emailInput" :value="__('E-mail')" />
                        <x-input wire:model.defer="state.email" type="email" id="emailInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                        <x-input-error for="state.email" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="websiteInput" :value="__('Website')" />
                        <x-input wire:model.defer="state.website" type="text" id="websiteInput" class="mt-1 block w-full rounded-xl sm:text-sm" placeholder="https://www.exemplo.com.br" />
                        <x-input-error for="state.website" class="mt-2" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="bioInput" :value="__('Bio')" />
                        <x-textarea wire:model.defer="state.bio" id="bioInput" class="mt-1 block w-full rounded-xl sm:text-sm" :placeholder="__('Escreva algumas frases sobre você')" />
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