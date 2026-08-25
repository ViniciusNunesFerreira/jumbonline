<x-guest-layout>
    <h1 class="font-urbanist text-2xl font-extrabold tracking-tight text-primary">Entrar</h1>
    <p class="mt-1 text-sm text-slate-500">
        Ainda não tem conta?
        <a href="{{ route('register') }}" class="font-semibold text-accent hover:text-primary">Criar agora</a>
    </p>

    @if(session('status'))
        <div class="mt-6 rounded-xl bg-accent/10 px-4 py-3 text-sm font-medium text-accent">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="mt-1.5 block w-full" />
            <x-input-error  class="mt-2" for="email"/>
        </div>
        <div>
            <x-input-label for="password" value="Senha" />
            <x-input id="password" type="password" name="password" required autocomplete="current-password" class="mt-1.5 block w-full" />
            <x-input-error  class="mt-2" for="password" />
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-600">
                <x-input type="checkbox" name="remember_me" class="h-4 w-4 rounded !shadow-none" />
                Lembrar de mim
            </label>
            <a href="{{ route('password.request') }}" class="font-medium text-purple hover:text-accent">Esqueceu a senha?</a>
        </div>
        <div class="flex justify-center pt-1">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            @error('g-recaptcha-response')<span class="text-sm text-warning">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="w-full rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 transition-transform hover:scale-[1.01] hover:bg-primary">
            Entrar
        </button>
    </form>
</x-guest-layout>