<div>
    <x-card>
        <x-slot:header>
            <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                {{ __('Cliente') }}
            </h3>
        </x-slot:header>
        <x-slot:content>
            <div class="border-t border-slate-200 divide-y divide-slate-200 -mx-4 -m-6 sm:-mx-6 dark:border-slate-200/10 dark:divide-slate-200/10">
                @if($order->customer)
                    <div class="flex items-center p-4 sm:py-5 sm:px-6">
                        <div class="flex-shrink-0 mr-4">
                            <img
                                src="{{ $order->customer->getFirstMediaUrl('avatar') }}"
                                alt="{{ $order->customer->name }}"
                                class="h-10 w-10 rounded-full bg-slate-200 object-center object-cover"
                            >
                        </div>
                        <div class="flex justify-between items-center w-full">
                            <a
                                href="{{ route('employee.customers.detail', $order->customer) }}"
                                class="text-sm font-medium hover:text-sky-600 dark:hover:text-sky-400"
                            >
                                {{ $order->customer->name }}
                            </a>
                            <x-heroicon-s-chevron-right class="w-5 h-5 text-slate-400" />
                        </div>
                    </div>
                    <div class="text-sm p-4 sm:py-5 sm:px-6">
                        <h4 class="font-medium uppercase text-xs">
                            {{ __('Contact information') }}
                        </h4>
                        <ul class="mt-3 space-y-1">
                            <li @class(['text-slate-500 dark:text-slate-400' => !$order->customer->email])>
                                {{ $order->customer->email ?? __('No email provided') }}
                            </li>
                            <li @class(['text-slate-500 dark:text-slate-400' => !$order->customer->phone])>
                                {{ $order->customer->phone ?? __('No phone number') }}
                            </li>
                        </ul>
                    </div>
                @endif

                @if($order->prison_unit)
                    <div class="p-4 sm:py-5 sm:px-6">
                        <h4 class="font-medium uppercase text-xs">
                            {{ __('Endereço para envio') }}
                        </h4>
                        <address class="mt-2 not-italic text-sm">
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

                            CEP: {{ $order->prison_unit->cep }}<br>

                            @if($order->prison_unit->phone)
                                {{ $order->prison_unit->phone }}<br>
                            @endif
                        </address>
                    </div>
                @endif

                @if($order->visitante)
                    <div class="p-4 sm:py-5 sm:px-6">
                        <h4 class="font-medium uppercase text-xs">
                            {{ __('Endereço Remetente') }}
                        </h4>
                        <address class="mt-2 not-italic text-sm">
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

                            CEP: {{ $order->visitante->cep }}<br>

                        </address>
                    </div>
                @endif

                @if($order->detento)

                    <div class="p-4 sm:py-5 sm:px-6">
                        <h4 class="font-medium uppercase text-xs">
                            {{ __('Informações do Detento') }}
                        </h4>

                        <address class="mt-2 not-italic text-sm">
                            {{ $order->detento->name }} <br>
                            Matricula: {{ $order->detento->matricula }}<br>
                            Raio: {{ $order->detento->raio }}<br>
                            Cela: {{ $order->detento->cela }}<br>
                        </address>
                    </div>

                @endif
            </div>
        </x-slot:content>
    </x-card>
</div>
