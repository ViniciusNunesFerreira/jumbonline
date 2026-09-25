<!DOCTYPE html>
<html
    x-cloak
    x-data="{ theme: $persist('light') }"
    x-bind:class="{ 'dark': theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full"
>
    <head>
        <meta charset="utf-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        >
        <meta
            name="robots"
            content="noindex, nofollow"
        >

        <!-- Title -->
        <title>{{ isset($title) ? $title . ' - ' . $generalSettings->store_name : $generalSettings->store_name }}</title>

        <!-- Favicon -->
        <link
            rel="icon"
            href="{{ $brandSettings->favicon_path ? Storage::url($brandSettings->favicon_path) : asset('img/favicon.png') }}"
        >

        <!-- Fonts -->
        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
        >
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        >
        <link
            href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        >

        <!-- Styles -->
        @livewireStyles
        @vite('resources/css/admin.css')
    </head>
    <body
        id="main"
        class="antialiased font-sans h-full bg-slate-50 dark:bg-slate-950"
    >
        <div
            x-data="{ sidebarOpen: false }"
            x-on:keydown.window.esc="sidebarOpen = false"
        >
            {{-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. --}}
            <div
                x-show="sidebarOpen"
                class="relative z-50 lg:hidden"
                role="dialog"
                aria-modal="true"
            >
                <div
                    x-show="sidebarOpen"
                    x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-primary-900/60 backdrop-blur-sm"
                ></div>

                <div class="fixed inset-0 flex">
                    <div
                        x-show="sidebarOpen"
                        x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="-translate-x-full"
                        x-on:click.away="sidebarOpen = false"
                        class="relative mr-16 flex w-full max-w-xs flex-1"
                    >
                        <div
                            x-show="sidebarOpen"
                            x-transition:enter="ease-in-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in-out duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute left-full top-0 flex w-16 justify-center pt-5"
                        >
                            <button
                                x-on:click="sidebarOpen = false"
                                type="button"
                                class="-m-2.5 p-2.5"
                            >
                                <span class="sr-only">{{ __('Close sidebar') }}</span>
                                <x-heroicon-o-x-mark class="h-6 w-6 text-white" />
                            </button>
                        </div>

                        <div class="sidebar-scroll flex grow flex-col gap-y-6 overflow-y-auto bg-white px-5 pb-4 dark:bg-slate-900 dark:ring-1 dark:ring-white/10">
                            <div class="flex h-16 shrink-0 items-center gap-3">
                                <img
                                    src="{{ $brandSettings->logo_path ? Storage::url($brandSettings->logo_path) : asset('img/logo.png') }}"
                                    alt="{{ config('app.name') }}"
                                    class="h-8 w-auto"
                                >
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Painel') }}</span>
                            </div>
                            @include('layouts.partials.admin-sidebar-nav')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Static sidebar for desktop --}}
            <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col">
                <div class="sidebar-scroll flex grow flex-col gap-y-6 overflow-y-auto border-r border-slate-200/70 bg-white px-5 pb-4 dark:bg-slate-900 dark:border-white/10">
                    <div class="flex h-16 shrink-0 items-center gap-3">
                        <x-site-logo :brand-settings="$brandSettings" size="md" class="mx-auto"/>
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Painel') }}</span>
                    </div>
                    @include('layouts.partials.admin-sidebar-nav')
                </div>
            </div>

            <div class="lg:pl-72">
                <div class="sticky top-0 z-40 lg:mx-auto lg:max-w-none">
                    <div class="flex h-16 items-center gap-x-4 border-b border-slate-200/70 bg-white/80 px-4 backdrop-blur-md sm:gap-x-6 sm:px-6 lg:px-8 dark:bg-slate-900/80 dark:border-white/10">
                        <button
                            x-on:click="sidebarOpen = true"
                            type="button"
                            class="-m-2.5 p-2.5 text-slate-700 lg:hidden dark:text-slate-400"
                        >
                            <span class="sr-only">{{ __('Open sidebar') }}</span>
                            <x-heroicon-o-bars-3
                                class="h-6 w-6"
                                aria-hidden="true"
                            />
                        </button>

                        <!-- Separator -->
                        <div
                            class="h-6 w-px bg-slate-200 lg:hidden dark:bg-white/5"
                            aria-hidden="true"
                        ></div>

                        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                            <div class="relative flex flex-1 items-center">
                                <button
                                    class="flex w-full max-w-sm items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-left text-sm text-slate-400 transition-colors hover:border-slate-300 hover:bg-white dark:border-white/10 dark:bg-white/5 dark:text-slate-500 dark:hover:bg-white/10"
                                    x-on:click="$dispatch('open-search')"
                                >
                                    <x-heroicon-o-magnifying-glass class="h-4 w-4 shrink-0" aria-hidden="true" />
                                    <span class="hidden sm:inline">{{ __('Buscar no painel...') }}</span>
                                    <kbd class="ml-auto hidden rounded-md bg-white px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 ring-1 ring-inset ring-slate-200 sm:inline dark:bg-slate-800 dark:ring-white/10">/</kbd>
                                </button>
                            </div>
                            <div class="flex items-center gap-x-3 lg:gap-x-4">
                                <x-dropdown>
                                    <x-slot:trigger>
                                        <button
                                            type="button"
                                            class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-primary dark:hover:bg-white/5 dark:hover:text-white"
                                        >
                                            <span class="sr-only">
                                                {{ __('Change theme') }}
                                            </span>
                                            <x-heroicon-o-sun
                                                class="h-5 w-5"
                                                x-show="theme === 'light'"
                                                x-cloak
                                            />
                                            <x-heroicon-o-moon
                                                class="h-5 w-5"
                                                x-show="theme === 'dark'"
                                                x-cloak
                                            />
                                        </button>
                                    </x-slot:trigger>
                                    <x-slot:content>
                                        <x-dropdown-link
                                            x-on:click="theme = 'light'"
                                            role="button"
                                            class="flex items-center space-x-2"
                                        >
                                            <x-heroicon-o-sun class="h-5 w-5" />
                                            <span>{{ __('Light') }}</span>
                                        </x-dropdown-link>
                                        <x-dropdown-link
                                            x-on:click="theme = 'dark'"
                                            role="button"
                                            class="flex items-center space-x-2"
                                        >
                                            <x-heroicon-o-moon class="h-5 w-5" />
                                            <span>{{ __('Dark') }}</span>
                                        </x-dropdown-link>
                                    </x-slot:content>
                                </x-dropdown>

                                <!-- Separator -->
                                <div
                                    class="hidden lg:block lg:h-6 lg:w-px lg:bg-slate-200 dark:bg-white/10"
                                    aria-hidden="true"
                                ></div>

                                <!-- Profile dropdown -->
                                <x-dropdown>
                                    <x-slot:trigger>
                                        <button
                                            type="button"
                                            class="-m-1.5 flex items-center rounded-lg p-1.5 transition-colors hover:bg-slate-100 dark:hover:bg-white/5"
                                            id="user-menu-button"
                                            :aria-expanded="open.toString()"
                                            aria-haspopup="true"
                                        >
                                            <span class="sr-only">{{ __('Open user menu') }}</span>
                                            <img
                                                class="h-8 w-8 flex-shrink-0 rounded-full bg-slate-100 ring-2 ring-white dark:bg-slate-800 dark:ring-slate-900"
                                                src="{{ auth()->user()->getFirstMediaUrl('avatar') }}"
                                                alt="{{ auth()->user()->name }}"
                                            >
                                            <span class="hidden lg:flex lg:items-center">
                                                <span
                                                    class="ml-3 text-sm font-semibold leading-6 text-primary dark:text-white"
                                                    aria-hidden="true"
                                                >
                                                    {{ auth()->user()->name }}
                                                </span>
                                                <x-heroicon-m-chevron-down
                                                    class="ml-1.5 h-4 w-4 text-slate-400"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </button>
                                    </x-slot:trigger>
                                    <x-slot:content>
                                        <x-dropdown-link href="{{ route('employee.profile') }}">
                                            {{ __('Personal profile') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link href="{{ route('employee.profile') }}">
                                            {{ __('Change password') }}
                                        </x-dropdown-link>
                                        <hr class="border-slate-200 dark:border-white/10" />
                                        <div class="relative cursor-pointer block px-4 py-2 text-sm leading-5 text-slate-700 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 transition duration-150 ease-in-out dark:text-slate-200 dark:focus:bg-slate-900/40 dark:hover:bg-slate-900/40">
                                            <livewire:employee.auth.logout />
                                        </div>
                                    </x-slot:content>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>

                <main class="mx-auto py-8 ">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <livewire:components.spotlight />

        <x-notification />

        <!-- Scripts -->
        @livewireScripts
        @vite('resources/js/admin.js')
        @stack('scripts')
    </body>
</html>