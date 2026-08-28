<div>
    <div x-data="{ selected: @entangle('selected') }">
        <div class="rounded-2xl border border-secondary bg-complement-500 p-5">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="font-urbanist text-sm font-semibold text-primary">Fotos da Carteirinha (Frente e Verso)</h3>
                <button
                    x-show="selected.length"
                    x-cloak
                    wire:click="$set('confirmingMediaDeletion', true)"
                    type="button"
                    class="text-sm font-semibold text-warning hover:text-primary"
                >
                    {{ trans_choice('Excluir :count arquivo|Excluir :count arquivos', count($selected)) }}
                </button>
            </div>

            <x-input-error for="media.*" class="mt-2" />

            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                @foreach($visitante->getMedia('gallery') as $medium)
                    <div class="group relative flex aspect-[3/4] items-center justify-center overflow-hidden rounded-xl border border-secondary bg-white">
                        <img src="{{ $medium->getUrl() }}" alt="{{ $medium->name }}" class="h-full w-full object-contain transition group-hover:scale-105" />
                        <div class="absolute inset-0 bg-primary/0 transition-colors group-hover:bg-primary/10"></div>
                        <x-input
                            wire:model="selected"
                            type="checkbox"
                            class="absolute left-2 top-2 !rounded !border-secondary !shadow-sm checked:!bg-accent"
                            value="{{ $medium->id }}"
                        />
                    </div>
                @endforeach

                <label for="mediaUpload" class="relative flex aspect-[3/4] cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-secondary bg-white text-center transition hover:border-accent">
                    <div wire:target="media" wire:loading.flex class="absolute inset-0 hidden items-center justify-center bg-white/90">
                        <x-loading-spinner class="h-8 w-8 text-accent" />
                    </div>
                    <x-heroicon-o-arrow-up-tray class="h-8 w-8 text-accent" />
                    <span class="px-2 text-xs font-semibold text-primary">Enviar foto</span>
                    <x-input wire:model="media" type="file" id="mediaUpload" class="sr-only" accept="image/*" multiple />
                </label>
            </div>
        </div>
    </div>

    <x-modal-alert wire:model.defer="confirmingMediaDeletion">
        <x-slot:title>Confirme sua ação</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500">
                {{ trans_choice('Tem certeza que deseja excluir :count arquivo?|Tem certeza que deseja excluir :count arquivos?', count($selected)) }}
                Esta ação não pode ser desfeita.
            </p>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click.prevent="delete" type="button" class="rounded-full bg-accent px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary sm:ml-3">
                Excluir
            </button>
            <button wire:click="$set('confirmingMediaDeletion', false)" type="button" class="mt-3 rounded-full border border-secondary px-6 py-2.5 text-sm font-semibold text-primary hover:bg-complement-500 sm:mt-0">
                Cancelar
            </button>
        </x-slot:footer>
    </x-modal-alert>
</div>