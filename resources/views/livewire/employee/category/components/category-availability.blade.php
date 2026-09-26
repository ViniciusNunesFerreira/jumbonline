<div>
    <form
        x-data="{ dirty: false, date: new Date(), published_at: @entangle('published_at'), scheduled_at: null }"
        x-init="flatpickr($refs.date, {
            enableTime: true,
            dateFormat: 'Z',
            minDate: Date.now(),
            defaultHour: date.getHours(),
            defaultMinute: date.getMinutes(),
            disableMobile: true,
            plugins: [new confirmDatePlugin({
                confirmIcon: '',
                confirmText: '{{ __('Agendar disponibilidade') }}',
                showAlways: true
            })],
            onClose: function(date, dateString) {
                if (dateString !== undefined) {
                    published_at = dateString;
                    scheduled_at = dateString;
                    dirty = true;
                }
            }
        })"
        x-on:category-availability-updated.window="dirty = false"
        wire:submit.prevent="save"
    >
        <x-card>
            <x-slot:header>
                <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                    <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Status da Categoria') }}
                    </h3>
                    <div x-show="dirty" class="flex-shrink-0">
                        <button type="submit" class="btn btn-link">
                            {{ __('Salvar') }}
                        </button>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content>
                <p @class(['text-sm', 'text-amber-600' => !$published_at, 'text-slate-600 dark:text-slate-300' => $published_at])>
                    @if(!$published_at)
                        {{ __('Esta categoria não está disponível.') }}
                    @elseif(\Carbon\Carbon::parse($published_at)->isPast())
                        {{ __('Esta categoria está disponível.') }}
                    @else
                        {{ __('Agendado para ') }}
                        <span x-text="scheduled_at ? new Date(scheduled_at) : new Date(Date.parse(published_at + ' {{ config('app.timezone') }}'))"></span>
                    @endif
                </p>
                <div class="mt-3 flex gap-3">
                    @if(!$published_at)
                        
                        <a    role="button"
                            x-on:click="published_at = '{{ now()->toIso8601ZuluString() }}'; dirty = !dirty"
                            class="btn btn-link cursor-pointer"
                        >
                            {{ __('Publicar') }}
                        </a>
                    @else
                        
                        <a    role="button"
                            x-on:click="published_at = null; dirty = !dirty"
                            class="btn btn-link cursor-pointer"
                        >
                            {{ __('Desabilitar') }}
                        </a>
                    @endif
                    <span x-ref="date" class="btn btn-link cursor-pointer">
                        {{ __('Agendar') }}
                    </span>
                </div>
            </x-slot:content>
        </x-card>
    </form>
</div>