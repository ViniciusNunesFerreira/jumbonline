<div>
    <x-slot:title>
        {{ __('Página inicial') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Página inicial') }}
                </h1>
            </div>

            <form
                wire:submit.prevent="save"
                x-data="{
                    title: @entangle('state.home_page_title').defer,
                    description: @entangle('state.home_page_description').defer,
                }"
            >
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Título e descrição no Google') }}</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Aparecem nos resultados de busca e ao compartilhar o link do site.') }}
                        </p>
                    </x-slot:header>
                    <x-slot:content class="space-y-5">
                        <div
                            x-show="(title || '').includes('Appliances and Gadgets') || (description || '').includes('home appliances')"
                            x-cloak
                            class="flex items-start gap-2 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-400"
                        >
                            <x-heroicon-s-exclamation-triangle class="mt-0.5 h-4 w-4 flex-shrink-0" />
                            <span>{{ __('Este ainda é o texto padrão do template original (em inglês, sobre eletrodomésticos). Troque pelo texto da Jumbonline para o Google exibir a mensagem certa.') }}</span>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4 dark:border-white/5 dark:bg-white/5">
                            <p class="truncate text-base text-[#1a0dab] dark:text-[#8ab4f8]" x-text="title || '{{ __('(sem título)') }}'"></p>
                            <p class="truncate text-sm text-emerald-700 dark:text-emerald-500">{{ config('app.url') }}</p>
                            <p class="mt-1 line-clamp-2 text-sm text-slate-600 dark:text-slate-300" x-text="description || '{{ __('(sem descrição)') }}'"></p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <x-input-label for="homePageTitleInput" :value="__('Título da página inicial')" />
                                <span class="text-xs" :class="(title || '').length > 70 ? 'text-red-500' : ((title || '').length > 60 ? 'text-amber-500' : 'text-slate-400')" x-text="(title || '').length + '/60'"></span>
                            </div>
                            <x-input
                                x-model="title"
                                type="text"
                                id="homePageTitleInput"
                                class="mt-1 block w-full rounded-xl sm:text-sm"
                                placeholder="{{ __('Ex.: Jumbonline — Kits jumbo para unidades prisionais') }}"
                            />
                            <x-input-error for="state.home_page_title" class="mt-2" />
                            <p class="mt-1 text-xs text-slate-400">{{ __('Ideal até 60 caracteres — o Google costuma cortar títulos maiores.') }}</p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <x-input-label for="homePageDescriptionInput" :value="__('Descrição da página inicial')" />
                                <span class="text-xs" :class="(description || '').length > 320 ? 'text-red-500' : ((description || '').length > 160 ? 'text-amber-500' : 'text-slate-400')" x-text="(description || '').length + '/160'"></span>
                            </div>
                            <x-textarea
                                x-model="description"
                                id="homePageDescriptionInput"
                                rows="3"
                                class="mt-1 block w-full rounded-xl sm:text-sm"
                            />
                            <x-input-error for="state.home_page_description" class="mt-2" />
                            <p class="mt-1 text-xs text-slate-400">{{ __('Ideal até 160 caracteres — o Google costuma cortar descrições maiores.') }}</p>
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
    </div>
</div>