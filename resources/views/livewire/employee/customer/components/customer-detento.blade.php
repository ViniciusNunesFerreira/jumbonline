<div>
    <x-card>
        <x-slot:header>
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                <div class="ml-4 mt-2">
                    <h2 class="text-base font-medium text-slate-900 dark:text-slate-100">
                        {{ __('Detento Cadastrado') }}
                    </h2>
                </div>
                
            </div>
        </x-slot:header>
        <x-slot:content class="-mt-5">
            @unless($customer->detentos()->count() > 0)
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Nenhum Detento definido.') }}
                </div>
            @else
                <address class="not-italic text-sm">
                    {{ optional($detento)->name }}<br>
                
                    Matricula: <strong> {{ optional($detento)->matricula }} </strong> <br>
                    
                    Raio: <strong> {{ optional($detento)->raio }} </strong><br>
                
                    Cela: <strong>{{ optional($detento)->cela }} </strong> <br>
                               
                    Unid. Prisional: <strong> {{ optional($detento->prison_unit)->name }} </strong> <br>

                </address>
            @endunless
            
        </x-slot:content>
    </x-card>
</div>
