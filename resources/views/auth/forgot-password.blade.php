<x-guest-layout>
    <div class="py-32">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                <x-slot:content class="!py-8 sm:!px-10">
                    <div class="mb-6 text-sm text-gray-600">
                        {{ __('Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e enviaremos por e-mail um link de redefinição de senha que permitirá que você escolha uma nova.') }}
                    </div>

                    <!-- Session Status -->
                    @if(session('status'))
                        <x-alert
                            class="mb-6"
                            type="success"
                            message="{{ session('status') }}"
                        />
                    @endif

                    <form
                        method="POST"
                        action="{{ route('password.email') }}"
                    >
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label
                                for="email"
                                :value="__('Seu Email')"
                            />

                            <x-input
                                id="email"
                                class="block mt-1 w-full sm:text-sm"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                            />

                            <x-input-error
                                for="email"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-6">
                            <button class="btn btn-primary w-full">
                                {{ __('Link de redefinição de senha') }}
                            </button>
                        </div>
                    </form>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</x-guest-layout>
