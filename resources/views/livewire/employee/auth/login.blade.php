<div class="relative flex min-h-full flex-col justify-center overflow-hidden bg-complement-500 py-12 sm:px-6 lg:px-8 dark:bg-slate-900">
    <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-accent-200/40 blur-3xl dark:bg-accent-500/10"></div>
    <div class="pointer-events-none absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-primary-200/40 blur-3xl dark:bg-primary-500/10"></div>

    <div class="relative sm:mx-auto sm:w-full sm:max-w-md flex items-center justify-center">

        <x-site-logo :brand-settings="$brandSettings" size="md" class="mx-auto"/>
        
    </div>

    <div
        x-data
        class="relative mt-8 sm:mx-auto sm:w-full sm:max-w-md"
    >
        <div
            x-on:login-error.window="$el.classList.add('animate-buzz'); setTimeout(() => $el.classList.remove('animate-buzz'), 1000)"
            class="relative overflow-hidden rounded-3xl bg-white px-6 py-10 shadow-xl shadow-primary-900/5 ring-1 ring-slate-900/5 sm:px-10 dark:bg-slate-800 dark:ring-white/10"
        >
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-accent-400 via-accent-500 to-primary-500"></div>

            <button
                x-on:click.prevent="theme = (theme === 'light' ? 'dark' : 'light')"
                class="absolute top-5 right-5"
            >
                <span
                    x-text="theme === 'light' ? '{{ __('Enable dark mode') }}' : '{{ __('Enable light mode') }}'"
                    class="sr-only"
                ></span>
                <svg
                    x-show="theme === 'light'"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 576 512"
                    class="h-5 w-5 text-slate-400 fill-current hover:text-primary-500 transition-colors"
                >
                    <path d="M180.7 120.5C81 120.5 .2 201.5 .2 301.4S81 482.2 180.7 482.2c48.9 0 93.3-19.5 125.8-51.2c4.7-4.6 5.9-11.8 2.9-17.6s-9.5-9.1-16-8c-7.7 1.3-15.6 2-23.6 2c-76 0-137.6-61.8-137.6-138c0-51.6 28.2-96.5 70-120.2c5.7-3.3 8.7-9.9 7.3-16.3s-6.9-11.2-13.4-11.8c-5-.4-10.2-.6-15.3-.6z" />
                    <path
                        class="opacity-40"
                        d="M268.3 93.4l10.4 36.4c1 3.4 4.1 5.8 7.7 5.8s6.7-2.4 7.7-5.8l10.4-36.4L340.8 83c3.4-1 5.8-4.1 5.8-7.7s-2.4-6.7-5.8-7.7L304.4 57.2 294 20.9c-1-3.4-4.1-5.8-7.7-5.8s-6.7 2.4-7.7 5.8L268.3 57.2 231.9 67.6c-3.4 1-5.8 4.1-5.8 7.7s2.4 6.7 5.8 7.7l36.4 10.4zm96.4 144.6l15.6 54.6c1.5 5.1 6.2 8.7 11.5 8.7s10-3.5 11.5-8.7l15.6-54.6 54.6-15.6c5.1-1.5 8.7-6.2 8.7-11.5s-3.5-10-8.7-11.5l-54.6-15.6-15.6-54.6c-1.5-5.1-6.2-8.7-11.5-8.7s-10 3.5-11.5 8.7l-15.6 54.6-54.6 15.6c-5.1 1.5-8.7 6.2-8.7 11.5s3.5 10 8.7 11.5l54.6 15.6z"
                    />
                </svg>
                <svg
                    x-show="theme === 'dark'"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    class="h-5 w-5 text-slate-400 fill-current hover:text-accent-400 transition-colors"
                >
                    <path d="M320 256C320 309 277 352 224 352C170.1 352 128 309 128 256C128 202.1 170.1 160 224 160C277 160 320 202.1 320 256z" />
                    <path
                        class="opacity-40"
                        d="M192 80C192 62.33 206.3 48 224 48C241.7 48 256 62.33 256 80C256 97.67 241.7 112 224 112C206.3 112 192 97.67 192 80zM192 432C192 414.3 206.3 400 224 400C241.7 400 256 414.3 256 432C256 449.7 241.7 464 224 464C206.3 464 192 449.7 192 432zM400 288C382.3 288 368 273.7 368 256C368 238.3 382.3 224 400 224C417.7 224 432 238.3 432 256C432 273.7 417.7 288 400 288zM48 224C65.67 224 80 238.3 80 256C80 273.7 65.67 288 48 288C30.33 288 16 273.7 16 256C16 238.3 30.33 224 48 224zM128 128C128 145.7 113.7 160 96 160C78.33 160 64 145.7 64 128C64 110.3 78.33 96 96 96C113.7 96 128 110.3 128 128zM352 416C334.3 416 320 401.7 320 384C320 366.3 334.3 352 352 352C369.7 352 384 366.3 384 384C384 401.7 369.7 416 352 416zM384 128C384 145.7 369.7 160 352 160C334.3 160 320 145.7 320 128C320 110.3 334.3 96 352 96C369.7 96 384 110.3 384 128zM96 352C113.7 352 128 366.3 128 384C128 401.7 113.7 416 96 416C78.33 416 64 401.7 64 384C64 366.3 78.33 352 96 352z"
                    />
                </svg>
            </button>

            <div class="text-center mb-8">
                <h2 class="font-sans text-2xl font-extrabold tracking-tight text-primary dark:text-white">
                    {{ __('Bem-vindo de volta') }}
                </h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Entre com seu e-mail e senha para acessar o painel.') }}
                </p>
            </div>

            <x-alert
                type="success"
                class="mb-4"
                :message="session('status')"
            />

            <form wire:submit.prevent="submit">
                <fieldset
                    wire:target="submit"
                    wire:loading.attr="disabled"
                    class="space-y-5"
                >
                    <div>
                        <x-input-label
                            for="email"
                            :value="__('E-mail')"
                            class="!text-sm !font-semibold !text-slate-700 dark:!text-slate-300"
                        />
                        <x-input
                            wire:model.defer="email"
                            id="email"
                            type="email"
                            name="email"
                            class="block mt-1.5 w-full rounded-xl sm:text-sm"
                            placeholder="voce@jumbonline.com.br"
                            required
                            autofocus
                        />
                        <x-input-error
                            for="email"
                            class="mt-2"
                        />
                    </div>

                    <div x-data="{ show: false }">
                        <x-input-label
                            for="password"
                            :value="__('Senha')"
                            class="!text-sm !font-semibold !text-slate-700 dark:!text-slate-300"
                        />
                        <div class="relative mt-1.5">
                            <x-input
                                wire:model.defer="password"
                                id="password"
                                type="password"
                                x-bind:type="show ? 'text' : 'password'"
                                name="password"
                                class="block w-full rounded-xl pr-10 sm:text-sm"
                            />
                            <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                            </button>
                        </div>
                        <x-input-error
                            for="password"
                            class="mt-2"
                        />
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center">
                            <x-input
                                wire:model.defer="remember_me"
                                type="checkbox"
                                id="remember-me"
                                class="h-4 w-4 !rounded !shadow-none text-accent-500 focus:ring-accent-500"
                            />
                            <label for="remember-me" class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                {{ __('Lembrar de mim') }}
                            </label>
                        </div>
                        
                        <a  href="{{ route('employee.forgot-password') }}"
                            class="btn btn-link text-xs"
                            tabindex="-1"
                        >
                            {{ __('Esqueceu a senha?') }}
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary block w-full !rounded-xl !py-2.5"
                    >
                        <span wire:loading.remove wire:target="submit">{{ __('Entrar') }}</span>
                        <span wire:loading wire:target="submit">{{ __('Entrando...') }}</span>
                    </button>
                </fieldset>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ now()->year }} {{ $generalSettings->store_name }}
        </p>
    </div>
</div>