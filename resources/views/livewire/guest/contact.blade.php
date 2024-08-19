<div>
    <x-slot:title>
        {{ __('Entre em Contato') }}
    </x-slot:title>

    <div class="bg-white max-w-7xl mx-auto py-16 px-6 sm:py-24 sm:px-8">
        <div class="gap-12 justify-between lg:flex">
            <div class="max-w-lg space-y-3">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ __('Como podemos ajudar?') }}
                </h1>
                <p>
                    Estamos aqui para ajudar e responder a qualquer dúvida que você possa ter. Estamos ansiosos para ouvir de você! Por favor preencha o formulário ou utilize os dados de contato abaixo.
                </p>
                <div>
                    <ul class="mt-6 flex flex-wrap gap-x-10 gap-y-6 items-center">
                        @if($generalSettings->contact_email)
                            <li class="flex items-center gap-x-3">
                                <div class="flex-none text-gray-400">
                                    <x-heroicon-o-envelope class="w-6 h-6" />
                                </div>
                                <p>
                                    {{ $generalSettings->contact_email }}
                                </p>
                            </li>
                        @endif

                        @if($generalSettings->contact_phone)
                            <li class="flex items-center gap-x-3">
                                <div class="flex-none text-gray-400">
                                    <x-heroicon-o-phone class="w-6 h-6" />
                                </div>
                                <p>
                                    {{ $generalSettings->contact_phone }}
                                </p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="flex-1 sm:max-w-lg lg:max-w-md">
                <form
                    wire:submit.prevent="sendMessage"
                    class="space-y-5"
                >
                    <div>
                        <x-input-label
                            for="nameInput"
                            :value="__('Seu Nome')"
                        />
                        <x-input
                            wire:model.defer="state.name"
                            id="nameInput"
                            class="block mt-1 w-full sm:text-sm"
                            type="text"
                            required
                        />
                        <x-input-error
                            for="state.name"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="emailInput"
                            :value="__('Email')"
                        />
                        <x-input
                            wire:model.defer="state.email"
                            id="emailInput"
                            class="block mt-1 w-full sm:text-sm"
                            type="email"
                            required
                        />
                        <x-input-error
                            for="state.email"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="phoneInput"
                            :value="__('Telefone (WhatsApp)')"
                        />
                        <x-input
                            wire:model.defer="state.phone"
                            id="phoneInput"
                            class="block mt-1 w-full sm:text-sm"
                            type="text"
                            required
                        />
                        <x-input-error
                            for="state.phone"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="messageInput"
                            :value="__('Mensagem')"
                        />
                        <x-textarea
                            wire:model.defer="state.message"
                            id="messageInput"
                            class="block mt-1 w-full sm:text-sm"
                            rows="4"
                            required
                        />
                        <x-input-error
                            for="state.message"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <button class="btn btn-primary btn-lg block w-full">
                            Enviar Mensagem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
