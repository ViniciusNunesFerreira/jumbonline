<div>
    <div x-data="{ selected: @entangle('selected') }">
        <x-card class="overflow-hidden">
            <x-slot:header>
                <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                    <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Fotos') }}
                    </h3>
                    <div class="flex-shrink-0">
                        <button
                            x-show="selected.length"
                            x-cloak
                            wire:click="$set('confirmingMediaDeletion', true)"
                            type="button"
                            class="btn p-0 text-red-500 hover:text-red-600 dark:hover:text-red-400"
                        >
                            {{ trans_choice('Excluir :count arquivo|Excluir :count arquivos', count($selected)) }}
                        </button>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content>
                <x-input-error for="media.*" class="mb-2" />
                <div @class(['grid grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-fr' => $product->hasMedia('gallery')])>
                    @foreach($product->getMedia('gallery') as $medium)
                        <div @class(['relative overflow-hidden border border-slate-100 group rounded-xl flex items-center justify-center dark:border-white/10', 'col-start-1 col-span-2 row-span-2' => $loop->first])>
                            <img
                                src="{{ $medium->getUrl() }}"
                                alt="{{ $medium->name }}"
                                class="h-full w-full object-cover object-center transition group-hover:scale-110"
                            />
                            <div class="absolute inset-0 rounded-xl bg-primary-900/0 transition-colors group-hover:bg-primary-900/40"></div>
                            <x-input
                                wire:model="selected"
                                type="checkbox"
                                class="absolute top-2 left-2 !rounded !shadow-none opacity-0 group-hover:opacity-100 text-accent-500 focus:ring-accent-500 dark:!bg-slate-900"
                                x-bind:class="{ 'opacity-100': selected.length }"
                                value="{{ $medium->id }}"
                            />
                        </div>
                    @endforeach
                    <label
                        for="mediaUpload"
                        class="py-4 relative flex items-center justify-center border-2 border-slate-200 border-dashed rounded-xl hover:border-accent-300 cursor-pointer transition group dark:border-white/10 dark:hover:border-accent-500/50"
                    >
                        <div
                            wire:target="media"
                            wire:loading.flex
                            class="hidden absolute inset-0 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900"
                        >
                            <x-loading-spinner class="h-10 w-10 text-accent-500" />
                        </div>
                        <div class="space-y-1 text-center">
                            <x-heroicon-m-arrow-up-tray class="mx-auto h-10 w-10 text-slate-300" />
                            <div class="flex text-sm text-slate-500">
                                <span class="font-semibold text-accent-600 group-hover:text-accent-500 group-hover:underline dark:text-accent-400">{{ __('Enviar arquivo') }}</span>
                                <x-input wire:model="media" type="file" id="mediaUpload" class="sr-only" multiple />
                            </div>
                        </div>
                    </label>
                </div>
            </x-slot:content>
        </x-card>
    </div>
    <x-modal-alert wire:model.defer="confirmingMediaDeletion">
        <x-slot:title>
            {{ __('Por favor, confirme sua ação!') }}
        </x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ trans_choice('Tem certeza de que deseja excluir :count arquivo?|Tem certeza de que deseja excluir :count arquivos?', count($selected)) }}
                {{ __('Esta ação não pode ser desfeita!') }}
            </p>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click.prevent="delete" type="button" class="btn btn-danger w-full sm:ml-3 sm:w-auto">
                {{ __('Deletar') }}
            </button>
            <button wire:click="$set('confirmingMediaDeletion', false)" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-alert>
</div>