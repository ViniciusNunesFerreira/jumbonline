<x-guest-layout>
    <h1 class="font-urbanist text-2xl font-extrabold tracking-tight text-primary">Criar sua conta</h1>
    <p class="mt-1 text-sm text-slate-500">
        Já tem conta?
        <a href="{{ route('login') }}" class="font-semibold text-accent hover:text-primary">Entrar</a>
    </p>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf
        <x-honeypot />
        <div>
            <x-input-label for="name" value="Nome completo" />
            <x-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" class="mt-1.5 block w-full" />
            <x-input-error  class="mt-2" for="name" />
        </div>
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" class="mt-1.5 block w-full" />
            <x-input-error  class="mt-2" for="email"/>
        </div>
        <div>
            <x-input-label for="phone" value="WhatsApp" />
            <div class="mt-1.5 flex gap-2">
                <select name="phone_country" class="rounded-xl border-secondary text-sm">
                    <option value="BR" selected>🇧🇷 +55</option>
                </select>
                <x-input id="phone" 
                    type="tel" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                    placeholder="(11) 99999-9999"
                    x-data
                    x-on:input="
                        let digits = $event.target.value.replace(/\D/g, '').slice(0, 11);
                        let result = '';
                        if (digits.length > 0) result = '(' + digits.slice(0, 2);
                        if (digits.length >= 3) result += ') ' + digits.slice(2, 7);
                        if (digits.length >= 8) result += '-' + digits.slice(7, 11);
                        $event.target.value = result;
                    " 
                    class="block w-full" />
            </div>
            <p class="mt-1.5 text-xs text-slate-400">Usamos só para avisar sobre o status do seu pedido — sem spam.</p>
            <x-input-error  class="mt-2" for="phone"/>
        </div>
        <div>
            <x-input-label for="password" value="Senha" />
            <x-input id="password" type="password" name="password" required autocomplete="new-password" class="mt-1.5 block w-full" />
            <p class="mt-1.5 text-xs text-slate-400">Mínimo 8 caracteres, com letra maiúscula e número.</p>
            <x-input-error  class="mt-2" for="password"/>
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Confirmar senha" />
            <x-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1.5 block w-full" />
            <x-input-error class="mt-2" for="password_confirmation"/>
        </div>
        <div class="flex justify-center pt-1">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            @error('g-recaptcha-response')<span class="text-sm text-warning">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="w-full rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 transition-transform hover:scale-[1.01] hover:bg-primary">
            Criar conta
        </button>
        <p class="text-center text-xs leading-relaxed text-slate-400">
            Ao criar sua conta, você concorda com nossos
            <a href="#" class="underline hover:text-accent">Termos de Uso</a> e
            <a href="#" class="underline hover:text-accent">Política de Privacidade</a>.
        </p>
    </form>
</x-guest-layout>