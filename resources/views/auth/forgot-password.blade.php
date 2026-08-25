<x-guest-layout>
    <h1 class="font-urbanist text-2xl font-extrabold tracking-tight text-primary">Esqueceu a senha?</h1>
    <p class="mt-2 text-sm text-slate-500">
        Sem problemas. Informe seu e-mail e mandamos um link pra você escolher uma nova.
    </p>

    @if(session('status'))
        <div class="mt-6 rounded-xl bg-accent/10 px-4 py-3 text-sm font-medium text-accent">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="Seu e-mail" />
            <x-input id="email" type="email" name="email" :value="old('email')" required autofocus class="mt-1.5 block w-full" />
            <x-input-error for="email" class="mt-2" />
        </div>
        <div class="flex justify-center pt-1">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            @error('g-recaptcha-response')<span class="text-sm text-warning">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="w-full rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">
            Enviar link de redefinição
        </button>
    </form>
</x-guest-layout>