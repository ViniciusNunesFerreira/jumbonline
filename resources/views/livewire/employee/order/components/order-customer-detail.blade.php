<div>
    <x-card>
        <x-slot:header>
            <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                {{ __('Cliente') }}
            </h3>
        </x-slot:header>
        <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
            <div class="divide-y divide-slate-100 dark:divide-white/5">
                @if($order->customer)
                    <div class="flex items-center p-4 sm:py-4 sm:px-6">
                        <div class="flex-shrink-0 mr-3.5">
                            <img
                                src="{{ $order->customer->getFirstMediaUrl('avatar') }}"
                                alt="{{ $order->customer->name }}"
                                class="h-10 w-10 rounded-full bg-slate-100 object-center object-cover ring-2 ring-white dark:bg-slate-800 dark:ring-slate-900"
                            >
                        </div>
                        <a href="{{ route('employee.customers.detail', $order->customer) }}" class="flex justify-between items-center w-full group">
                            <span class="text-sm font-semibold text-primary group-hover:text-accent-600 dark:text-slate-200 dark:group-hover:text-accent-400">
                                {{ $order->customer->name }}
                            </span>
                            <x-heroicon-s-chevron-right class="w-4 h-4 text-slate-300 group-hover:text-accent-500" />
                        </a>
                    </div>
                    <div class="text-sm p-4 sm:py-4 sm:px-6">
                        <h4 class="flex items-center gap-1.5 font-semibold uppercase text-[11px] tracking-wider text-slate-400 dark:text-slate-500">
                            <x-heroicon-o-user class="h-3.5 w-3.5" />
                            {{ __('Informações de contato') }}
                        </h4>
                        <ul class="mt-2 space-y-1 text-slate-600 dark:text-slate-300">
                            <li @class(['text-slate-400' => !$order->customer->email])>
                                {{ $order->customer->email ?? __('E-mail não informado') }}
                            </li>
                            <li @class(['text-slate-400' => !$order->customer->phone])>
                                {{ $order->customer->phone ?? __('Telefone não informado') }}
                            </li>
                        </ul>
                    </div>
                @endif

                @if($order->prison_unit)
                    <div class="p-4 sm:py-4 sm:px-6">
                        <h4 class="flex items-center gap-1.5 font-semibold uppercase text-[11px] tracking-wider text-slate-400 dark:text-slate-500">
                            <x-heroicon-o-building-office class="h-3.5 w-3.5" />
                            {{ __('Endereço para envio') }}
                        </h4>
                        <address class="mt-2 not-italic text-sm text-slate-600 dark:text-slate-300">
                            {{ $order->prison_unit->name }}<br>

                            @if($order->prison_unit->logradouro)
                                {{ $order->prison_unit->logradouro }}, {{ $order->prison_unit->numero }}<br>
                            @endif

                            @if($order->prison_unit->bairro)
                                {{ $order->prison_unit->bairro }}<br>
                            @endif

                            @if($order->prison_unit->cidade)
                                {{ $order->prison_unit->cidade }}
                            @endif

                            @if($order->prison_unit->estado)
                                {{ $order->prison_unit->estado }}<br>
                            @endif

                            {{ __('CEP') }}: {{ $order->prison_unit->cep }}<br>

                            @if($order->prison_unit->phone)
                                {{ $order->prison_unit->phone }}<br>
                            @endif
                        </address>
                    </div>
                @endif

                @if($order->visitante)
                    <div class="p-4 sm:py-4 sm:px-6">
                        <h4 class="flex items-center gap-1.5 font-semibold uppercase text-[11px] tracking-wider text-slate-400 dark:text-slate-500">
                            <x-heroicon-o-user-circle class="h-3.5 w-3.5" />
                            {{ __('Endereço Remetente') }}
                        </h4>
                        <address class="mt-2 not-italic text-sm text-slate-600 dark:text-slate-300">
                            {{ $order->visitante->nome }}<br>

                            @if($order->visitante->logradouro)
                                {{ $order->visitante->logradouro }}, {{ $order->visitante->numero }}<br>
                            @endif

                            @if($order->visitante->bairro)
                                {{ $order->visitante->bairro }}<br>
                            @endif

                            @if($order->visitante->cidade)
                                {{ $order->visitante->cidade }}
                            @endif

                            @if($order->visitante->estado)
                                {{ $order->visitante->estado }}<br>
                            @endif

                            {{ __('CEP') }}: {{ $order->visitante->cep }}<br>
                        </address>
                    </div>
                @endif

                @if($order->detento)
                    <div class="p-4 sm:py-4 sm:px-6">
                        <h4 class="flex items-center gap-1.5 font-semibold uppercase text-[11px] tracking-wider text-slate-400 dark:text-slate-500">
                            <x-heroicon-o-identification class="h-3.5 w-3.5" />
                            {{ __('Informações do Detento') }}
                        </h4>
                        <address class="mt-2 not-italic text-sm text-slate-600 dark:text-slate-300">
                            {{ $order->detento->name }}<br>
                            {{ __('Matrícula') }}: {{ $order->detento->matricula }}<br>
                            {{ __('Raio') }}: {{ $order->detento->raio }}<br>
                            {{ __('Cela') }}: {{ $order->detento->cela }}<br>
                        </address>
                    </div>
                @endif
            </div>
        </x-slot:content>
    </x-card>
</div>