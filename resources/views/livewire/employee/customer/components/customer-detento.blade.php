<div>
    <x-card>
        <x-slot:header>
            <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                {{ __('Detento Cadastrado') }}
            </h2>
        </x-slot:header>
        <x-slot:content>
            @unless($customer->detentos()->count() > 0)
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Nenhum Detento definido.') }}
                </p>
            @else
                <address class="not-italic text-sm text-slate-600 dark:text-slate-300 space-y-0.5">
                    <p class="font-semibold text-primary dark:text-slate-200">{{ optional($detento)->name }}</p>
                    <p>{{ __('Matrícula') }}: <span class="font-medium text-slate-700 dark:text-slate-200">{{ optional($detento)->matricula }}</span></p>
                    <p>{{ __('Raio') }}: <span class="font-medium text-slate-700 dark:text-slate-200">{{ optional($detento)->raio }}</span></p>
                    <p>{{ __('Cela') }}: <span class="font-medium text-slate-700 dark:text-slate-200">{{ optional($detento)->cela }}</span></p>
                    <p>{{ __('Unid. Prisional') }}: <span class="font-medium text-slate-700 dark:text-slate-200">{{ optional($detento->prison_unit)->name }}</span></p>
                </address>
            @endunless
        </x-slot:content>
    </x-card>
</div>