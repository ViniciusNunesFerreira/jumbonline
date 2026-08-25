<x-guest-layout>
    <h1 class="font-urbanist text-2xl font-extrabold tracking-tight text-primary">Confirme sua senha</h1>
    <p class="mt-2 text-sm text-slate-500">Esta é uma área segura — confirme sua senha antes de continuar.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <x-input-label for="password" value="Senha" />
            <x-input id="password" type="password" name="password" required autofocus class="mt-1.5 block w-full" />
            <x-input-error for="password" class="mt-2" />
        </div>
        <button type="submit" class="w-full rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">
            Confirmar
        </button>
    </form>
</x-guest-layout>