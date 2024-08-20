<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Perfil') }}
    </x-slot:title>

    <div class="py-16 sm:py-24">
        <div class="mx-auto max-w-7xl sm:px-2 lg:px-8">
            <div class="mx-auto max-w-2xl px-4 lg:max-w-4xl lg:px-0">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    {{ __('Perfil') }}
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    {{ __("Atualize as informações de perfil e senha da sua conta.") }}
                </p>
                <div class="mt-16 space-y-12">
                    <livewire:customer.profile.components.personal-information />

                    <livewire:customer.profile.components.change-password />
                </div>
            </div>
        </div>
    </div>
</div>
