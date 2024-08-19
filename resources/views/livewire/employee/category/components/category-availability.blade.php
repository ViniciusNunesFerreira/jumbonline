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
                confirmText: '{{ __('Schedule availability') }}',
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
                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                            Status da Categoria
                        </h3>
                    </div>
                    <div
                        x-show="dirty"
                        class="ml-4 mt-2 flex-shrink-0"
                    >
                        <button
                            type="submit"
                            class="btn btn-link"
                        >
                            {{ __('Salvar') }}
                        </button>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content class="-mt-5">
                <p class="text-sm -mt-5 @if(!$published_at)  text-warning-500  @else  text-secondary  @endif">
                    @if(!$published_at)
                        {{ __('Esta categoria não está disponível.') }}
                    @elseif(\Carbon\Carbon::parse($published_at)->isPast())
                        {{ __('Esta categoria está disponível.') }}
                    @else
                        {{ __('Agendado para ') }}
                        <span x-text="scheduled_at ? new Date(scheduled_at) : new Date(Date.parse(published_at + ' {{ config('app.timezone') }}'))"></span>
                    @endif
                </p>
                <div class="mt-4 space-x-4 flex justify-around">
                    @if(!$published_at)
                        <a
                            role="button"
                            x-on:click="published_at = '{{ now()->toIso8601ZuluString() }}'; dirty = !dirty"
                            class="btn btn-link cursor-pointer"
                        >
                            {{ __('Publicar') }}
                        </a>
                    @else
                        <a
                            role="button"
                            x-on:click="published_at = null; dirty = !dirty"
                            class="btn btn-link cursor-pointer"
                        >
                            {{ __('Desabilitar') }}
                        </a>
                    @endif
                    <span
                        x-ref="date"
                        class="btn btn-link cursor-pointer"
                    >
                        {{ __('Agendar') }}
                    </span>
                </div>
            </x-slot:content>
        </x-card>
    </form>
</div>
