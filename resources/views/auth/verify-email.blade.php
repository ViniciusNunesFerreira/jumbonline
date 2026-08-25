<x-guest-layout>
    <h1 class="font-urbanist text-2xl font-extrabold tracking-tight text-primary">Confirme seu e-mail</h1>
    <p class="mt-3 text-sm leading-relaxed text-slate-500">
        Antes de finalizar uma compra, precisamos confirmar seu e-mail. Clicamos no link que te enviamos — se não chegou, reenviamos abaixo.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-6 rounded-xl bg-accent/10 px-4 py-3 text-sm font-medium text-accent">
            Novo link de verificação enviado para o e-mail cadastrado.
        </div>
    @endif

    <div class="mt-8 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">
                Reenviar e-mail
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-slate-400 hover:text-accent">Sair</button>
        </form>
    </div>
</x-guest-layout>