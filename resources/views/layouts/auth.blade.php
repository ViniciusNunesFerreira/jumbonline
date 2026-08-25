<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ config('app.name', 'Jumbonline') }}</title>
    <link rel="icon" href="{{ $brandSettings->favicon_path ? Storage::url($brandSettings->favicon_path) : asset('img/favicon.png') }}">
    @livewireStyles
    @vite('resources/css/guest.css')
</head>
<body class="bg-complement-500 font-sans antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-6 py-12">
        <a href="/" class="mb-8">
            <x-site-logo :brand-settings="$brandSettings" size="md" />
        </a>

        <div class="w-full max-w-md rounded-3xl border border-secondary bg-white p-8 shadow-sm sm:p-10">
            {{ $slot }}
        </div>

        <a href="/" class="mt-6 text-sm text-slate-400 hover:text-accent">← Voltar para a loja</a>
    </div>

    @livewireScripts
    @vite('resources/js/guest.js')
</body>
</html>