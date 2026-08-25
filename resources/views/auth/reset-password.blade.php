<x-guest-layout>
    <h1 class="font-urbanist text-2xl font-extrabold tracking-tight text-primary">Redefinir senha</h1>

    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-input id="email" type="email" name="email" :value="old('email', $request->email)" required readonly class="mt-1.5 block w-full" />
            <x-input-error for="email" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" value="Nova senha" />
            <x-input id="password" type="password" name="password" required autofocus class="mt-1.5 block w-full" />
            <x-input-error for="password" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Confirme a nova senha" />
            <x-input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1.5 block w-full" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
        <div class="flex justify-center pt-1">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            @error('g-recaptcha-response')<span class="text-sm text-warning">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="w-full rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">
            Redefinir senha
        </button>
    </form>
</x-guest-layout>