<x-guest-layout>
    <div class="py-32">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                <x-slot:content class="!py-8 sm:!px-10">
                    <div class="mb-6 text-sm text-gray-600">
                        {{ __('Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos prazer em lhe enviar outro.') }}
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <x-alert
                            class="mb-6"
                            type="success"
                            message="{{ __('Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.') }}"
                        />
                    @endif

                    <div class="mt-6 flex items-center justify-between">
                        <form
                            method="POST"
                            action="{{ route('verification.send') }}"
                        >
                            @csrf

                            <div>
                                <button class="btn btn-primary w-full">
                                    {{ __('Reenviar e-mail de verificação') }}
                                </button>
                            </div>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-link"
                            >
                                {{ __('Sair') }}
                            </button>
                        </form>
                    </div>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</x-guest-layout>
