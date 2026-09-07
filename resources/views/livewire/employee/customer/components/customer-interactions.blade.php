<div>
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="font-display font-medium text-base text-slate-900 dark:text-slate-200">
                    {{ __('Diário de bordo do cliente') }}
                </h3>
                <button
                    wire:click="openForm"
                    type="button"
                    class="btn btn-default btn-xs"
                >
                    <x-heroicon-m-plus class="w-4 h-4 mr-1" />
                    {{ __('Registrar contato') }}
                </button>
            </div>
        </x-slot:header>
        <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
            @forelse($this->interactions as $interaction)
                <div class="flex gap-3 px-4 py-4 sm:px-6 border-b border-slate-100 last:border-b-0 dark:border-white/5">
                    <div class="flex-shrink-0 mt-0.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                            <x-dynamic-component :component="$interaction->channel->icon()" class="w-4 h-4 text-slate-500 dark:text-slate-400" />
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-badge :type="$interaction->type->badgeType()" size="xs">
                                {{ $interaction->type->label() }}
                            </x-badge>
                            <span class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $interaction->channel->label() }}
                            </span>
                            <span class="text-xs text-slate-400 dark:text-slate-500">
                                &middot; {{ $interaction->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">
                            {{ $interaction->description }}
                        </p>
                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                            {{ $interaction->employee?->name ?? __('Sistema') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 sm:px-6 text-center">
                    <x-heroicon-o-chat-bubble-left-right class="mx-auto h-8 w-8 text-slate-400" />
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Nenhuma interação registrada ainda.') }}
                    </p>
                </div>
            @endforelse
        </x-slot:content>
    </x-card>

    <x-modal-dialog wire:model.defer="showForm">
        <x-slot:title>
            {{ __('Registrar contato') }}
        </x-slot:title>
        <x-slot:content>
            <form wire:submit.prevent="save" class="grid gap-4">
                <div>
                    <x-input-label for="channel" :value="__('Canal')" />
                    <x-select wire:model="channel" id="channel" class="mt-1">
                        <option value="">{{ __('Selecione...') }}</option>
                        @foreach($this->channels as $case)
                            <option value="{{ $case->name }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="channel" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="type" :value="__('Tipo de registro')" />
                    <x-select wire:model="type" id="type" class="mt-1">
                        <option value="">{{ __('Selecione...') }}</option>
                        @foreach($this->types as $case)
                            <option value="{{ $case->name }}">{{ $case->label() }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="type" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Descrição')" />
                    <x-textarea wire:model="description" id="description" rows="4" class="mt-1" placeholder="{{ __('O que foi conversado ou tratado com o cliente...') }}" />
                    <x-input-error for="description" class="mt-2" />
                </div>
            </form>
        </x-slot:content>
        <x-slot:footer>
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                type="button"
                class="btn btn-primary w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Salvar') }}
            </button>
            <button
                x-on:click="show = false"
                type="button"
                class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto"
            >
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-dialog>
</div>