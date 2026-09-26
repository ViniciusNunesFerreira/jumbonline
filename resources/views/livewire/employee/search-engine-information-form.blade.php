<div>
    <form
        x-data="{
            isEditing: false,
            seoTitle: @entangle('model.seo_title'),
            seoDescription: @entangle('model.seo_description'),
        }"
        x-on:saved.window="isEditing = false"
        wire:submit.prevent="save"
    >
        <x-card class="relative overflow-hidden">
            <x-slot:header>
                <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                    <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Prévia na busca do Google') }}
                    </h3>
                    <div class="flex-shrink-0">
                        <button x-show="isEditing" type="submit" class="btn btn-link">
                            {{ __('Salvar') }}
                        </button>
                        <button x-show="!isEditing" x-on:click="isEditing = true" type="button" class="btn btn-link">
                            {{ __('Editar') }}
                        </button>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content class="space-y-5">
                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4 dark:border-white/5 dark:bg-white/5">
                    <p class="truncate text-base text-[#1a0dab] dark:text-[#8ab4f8]" x-text="seoTitle || '{{ __('(sem título de SEO — o título da página é usado)') }}'"></p>
                    <p class="truncate text-sm text-emerald-700 dark:text-emerald-500">
                        @if($model instanceof \App\Models\Article)
                            {{ config('app.url') . '/blog/articles/' . $model->slug }}
                        @elseif($model instanceof \App\Models\Collection)
                            {{ config('app.url') . '/collections/' . $model->slug }}
                        @elseif($model instanceof \App\Models\Product)
                            {{ config('app.url') . '/products/' . $model->slug }}
                        @endif
                    </p>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600 dark:text-slate-300" x-text="seoDescription || '{{ __('(sem descrição de SEO — o resumo da página é usado)') }}'"></p>
                </div>

                <div x-cloak x-show="isEditing" class="space-y-5 border-t border-slate-100 pt-5 dark:border-white/5">
                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="seo-title" :value="__('Título da página (SEO)')" />
                            <span class="text-xs" :class="seoTitle.length > 70 ? 'text-red-500' : (seoTitle.length > 55 ? 'text-amber-500' : 'text-slate-400')" x-text="seoTitle.length + '/70'"></span>
                        </div>
                        <x-input
                            x-model="seoTitle"
                            type="text"
                            id="seo-title"
                            name="seo-title"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                            maxlength="70"
                        />
                        <x-input-error for="model.seo_title" class="mt-2" />
                        <p class="mt-1 text-xs text-slate-400">{{ __('Se deixado em branco, o Google usa o título comum da página.') }}</p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="seo-description" :value="__('Descrição (SEO)')" />
                            <span class="text-xs" :class="seoDescription.length > 320 ? 'text-red-500' : (seoDescription.length > 160 ? 'text-amber-500' : 'text-slate-400')" x-text="seoDescription.length + '/320'"></span>
                        </div>
                        <x-textarea
                            x-model="seoDescription"
                            id="seo-description"
                            name="seo-description"
                            rows="3"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                        />
                        <x-input-error for="model.seo_description" class="mt-2" />
                        <p class="mt-1 text-xs text-slate-400">{{ __('Ideal até 160 caracteres — o Google costuma cortar descrições maiores. Se deixado em branco, o resumo da página é usado.') }}</p>
                    </div>

                    <div>
                        <x-input-label for="urlHandleInput" :value="__('URL amigável')" />
                        <x-input
                            wire:model.lazy="model.slug"
                            type="text"
                            id="urlHandleInput"
                            name="urlHandleInput"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                        />
                        <x-input-error for="model.slug" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
        </x-card>
    </form>
</div>