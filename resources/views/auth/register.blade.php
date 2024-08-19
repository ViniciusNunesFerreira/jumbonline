<x-guest-layout>
    <div class="py-32">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="text-center text-3xl font-bold tracking-tight text-slate-900">
                {{ __('Criar uma Conta') }}
            </h1>
            <p class="mt-2 text-center text-sm text-slate-600">
                {{ __('Já tem uma conta?') }}
                <a
                    href="{{ route('login') }}"
                    class="btn btn-link"
                >
                    {{ __('Faça login aqui') }}
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                <x-slot:content class="!py-8 sm:!px-10">
                    <form
                        method="POST"
                        action="{{ route('register') }}"
                    >
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label
                                for="name"
                                :value="__('Seu Nome')"
                            />

                            <x-input
                                id="name"
                                class="block mt-1 w-full sm:text-sm"
                                type="text"
                                name="name"
                                :value="old('name')"
                                required
                                autofocus
                            />

                            <x-input-error
                                for="name"
                                class="mt-2"
                            />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-6">
                            <x-input-label
                                for="email"
                                :value="__('Email')"
                            />

                            <x-input
                                id="email"
                                class="block mt-1 w-full sm:text-sm"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                            />

                            <x-input-error
                                for="email"
                                class="mt-2"
                            />
                        </div>

                        <!-- Password -->
                        <div class="mt-6">
                            <x-input-label
                                for="password"
                                :value="__('Senha')"
                            />

                            <x-input
                                id="password"
                                class="block mt-1 w-full sm:text-sm"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                            />

                            <x-input-error
                                for="password"
                                class="mt-2"
                            />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-6">
                            <x-input-label
                                for="password_confirmation"
                                :value="__('Confirmar Senha')"
                            />

                            <x-input
                                id="password_confirmation"
                                class="block mt-1 w-full sm:text-sm"
                                type="password"
                                name="password_confirmation"
                                required
                            />

                            <x-input-error
                                for="password_confirmation"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-6">
                            <button
                                type="submit"
                                class="btn btn-primary w-full"
                            >
                                {{ __('Cadastrar') }}
                            </button>
                        </div>
                    </form>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</x-guest-layout>
