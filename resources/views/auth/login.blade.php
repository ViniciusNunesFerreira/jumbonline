<x-guest-layout>
    <div class="py-32">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="text-center text-3xl font-bold tracking-tight text-slate-900">
                {{ __('Faça login em sua conta') }}
            </h1>
            <p class="mt-2 text-center text-sm text-slate-600">
                {{ __('Ou') }}
                <a
                    href="{{ route('register') }}"
                    class="btn btn-link"
                >
                    {{ __('Criar uma conta para começar') }}
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                <x-slot:content class="!py-8 sm:!px-10">
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
                        action="{{ route('login') }}"
                    >
                        @csrf

                        <!-- Email Address -->
                        <div>
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
                                autofocus
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
                                autocomplete="current-password"
                            />

                            <x-input-error
                                for="password"
                                class="mt-2"
                            />
                        </div>

                        <div class="mt-6 flex items-center justify-center flex-col">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                                <span class="text-warning mt-1">@lang($message)</span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <x-input
                                    type="checkbox"
                                    name="remember_me"
                                    id="remember_me"
                                    class="h-4 w-4 !rounded !shadow-none"
                                />

                                <x-input-label
                                    for="remember_me"
                                    :value="__('Lembrar de mim')"
                                    class="ml-2"
                                />
                            </div>
                            <div class="text-sm">
                                <a
                                    href="{{ route('password.request') }}"
                                    class="btn btn-link"
                                >
                                    {{ __('Esqueceu sua senha?') }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button class="btn btn-primary w-full">
                                {{ __('Entrar') }}
                            </button>
                        </div>
                    </form>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</x-guest-layout>
