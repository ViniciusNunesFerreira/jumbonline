<div>
    <form wire:submit.prevent="save">
        <x-card class="relative overflow-hidden">
            <x-slot:content>
                <fieldset
                    wire:target="save"
                    wire:loading.delay.attr="disabled"
                    class="grid grid-cols-1 gap-4"
                >
                    <div>
                        <x-input-label for="title" :value="__('Título')" />
                        <x-input
                            wire:model.lazy="category.title"
                            id="title"
                            type="text"
                            name="title"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                            :placeholder="__('Digite nome da categoria')"
                        />
                        <x-input-error for="category.title" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="quantity" :value="__('Máx. permitido')" />
                        <x-input
                            wire:model.lazy="category.quantity"
                            id="quantity"
                            type="number"
                            name="quantity"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                            min="0"
                            step="1"
                        />
                        <x-input-error for="category.quantity" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="slug" :value="__('URL handle')" />

                        <div class="mt-1 flex rounded-xl shadow-sm">
                            <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-slate-500 sm:text-sm dark:bg-white/5 dark:border-white/10">
                                {{ config('app.url') }}/categories/
                            </span>
                            <x-input
                                wire:model.defer="category.slug"
                                type="text"
                                id="slug"
                                class="block w-full min-w-0 flex-1 !rounded-l-none !rounded-r-xl !shadow-none sm:text-sm"
                            />
                        </div>

                        <x-input-error for="category.slug" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Descrição')" />
                        <div class="mt-1 border border-slate-200 rounded-xl px-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                            <x-tiptap
                                wire:target="save"
                                wire:loading.delay.class="opacity-50"
                                wire:model="category.description"
                            />
                        </div>
                        <x-input-error for="category.description" class="mt-2" />
                    </div>
                </fieldset>
            </x-slot:content>
            <x-slot:footer>
                <div class="flex items-center justify-end">
                    <button
                        wire:target="save"
                        wire:loading.attr="disabled"
                        type="submit"
                        class="btn btn-primary"
                    >
                        {{ __('Salvar') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-card>
    </form>

    <div
        x-data="{ addFromURL: false, selectedImage: null }"
        x-on:upload-image-success.window="addFromURL = false; selectedImage = $event.detail.imageId;"
    >
        <x-modal-dialog wire:model.defer="showImageModal">
            <x-slot:title>
                {{ __('Imagem') }}
            </x-slot:title>
            <x-slot:content>
                <div x-show="!addFromURL" x-transition:enter.duration.150ms x-transition:leave.duration.50ms>
                    <div class="inline-flex rounded-xl">
                        <button
                            x-on:click="$refs.imageInput.click()"
                            type="button"
                            class="relative inline-flex items-center rounded-l-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 focus:z-10 focus:border-accent-500 focus:outline-none focus:ring-1 focus:ring-accent-500 dark:bg-slate-800 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-700"
                        >
                            <x-heroicon-m-photo class="-ml-1 mr-2 w-5 h-5" />
                            {{ __('Adicionar nova') }}
                        </button>
                        <div class="relative -ml-px block">
                            <x-dropdown align="left">
                                <x-slot:trigger>
                                    <button
                                        type="button"
                                        class="relative inline-flex items-center rounded-r-xl border border-slate-200 bg-white px-2 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50 focus:z-10 focus:border-accent-500 focus:outline-none focus:ring-1 focus:ring-accent-500 dark:bg-slate-800 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-700"
                                    >
                                        <span class="sr-only">{{ __('Mais opções') }}</span>
                                        <x-heroicon-m-chevron-down class="h-5 w-5" />
                                    </button>
                                </x-slot:trigger>
                                <x-slot:content>
                                    <x-dropdown-link x-on:click="addFromURL = !addFromURL" role="button">
                                        {{ __('Adicionar do URL') }}
                                    </x-dropdown-link>
                                </x-slot:content>
                            </x-dropdown>
                        </div>
                        <button
                            x-show="selectedImage"
                            x-on:click.prevent="if(confirm('{{ __('Tem certeza de que deseja excluir esta imagem?') }}')) $wire.deleteImage(selectedImage); selectedImage = null;"
                            type="button"
                            class="btn btn-outline-danger !rounded-xl ml-4"
                        >
                            <x-heroicon-m-trash class="h-5 w-5" />
                        </button>
                    </div>
                    <x-input wire:model.defer="image" x-ref="imageInput" type="file" class="hidden" />
                </div>
                <div x-show="addFromURL" x-transition:enter.duration.150ms x-transition:leave.duration.50ms>
                    <form wire:submit.prevent="uploadImageFromURL">
                        <fieldset wire:loading.attr="disabled">
                            <x-input-label for="imageUrl" class="sr-only">{{ __('URL da imagem') }}</x-input-label>
                            <div class="flex items-center">
                                <div class="flex flex-1 rounded-xl">
                                    <div class="relative flex flex-grow items-stretch focus-within:z-10">
                                        <x-input
                                            wire:model.defer="imageUrl"
                                            type="text"
                                            id="imageUrl"
                                            class="block w-full !rounded-r-none !rounded-l-xl sm:text-sm"
                                            placeholder="https://"
                                        />
                                    </div>
                                    <button type="submit" class="btn btn-primary !rounded-l-none !rounded-r-xl">
                                        <x-heroicon-m-arrow-up-tray class="-ml-1 mr-2 h-5 w-5 text-white" />
                                        <span>{{ __('Enviar') }}</span>
                                    </button>
                                </div>
                                <button x-on:click="addFromURL = false" type="button" class="ml-3 inline-flex items-center py-2">
                                    <x-heroicon-m-x-mark class="w-5 h-5" />
                                </button>
                            </div>
                            <x-input-error for="imageUrl" class="mt-2" />
                        </fieldset>
                    </form>
                </div>
                <ul class="mt-8 grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    <li wire:target="image, imageUrl" wire:loading class="relative">
                        <div class="block w-full aspect-w-10 aspect-h-7 rounded-xl bg-slate-100 overflow-hidden dark:bg-slate-800">
                            <x-loading-spinner class="absolute inset-0 m-auto w-5 h-5" />
                        </div>
                    </li>
                    @foreach ($this->category->getMedia('images')->reverse() as $image)
                        <li class="relative">
                            <div
                                class="group block w-full aspect-w-10 aspect-h-7 rounded-xl bg-slate-100 overflow-hidden"
                                :class="{ 'ring-2 ring-offset-2 ring-accent-500 dark:ring-offset-slate-800': selectedImage === {{ $image->id }} }"
                            >
                                <img
                                    src="{{ $image->getUrl() }}"
                                    alt="{{ $image->name }}"
                                    class="object-cover pointer-events-none"
                                    :class="{ 'group-hover:opacity-75': selectedImage !== {{ $image->id }} }"
                                >
                                <button x-on:click="selectedImage = {{ $image->id }}" class="absolute inset-0 focus:outline-none">
                                    <span class="sr-only">{{ __('Selecionar esta imagem') }}</span>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-slot:content>
            <x-slot:footer>
                <button
                    x-bind:disabled="!selectedImage"
                    x-on:click="$wire.insertImage(selectedImage); selectedImage = null"
                    type="button"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Inserir') }}
                </button>
                <button x-on:click="$wire.set('showImageModal', false)" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </div>
</div>