<div>
    <x-slot:title>{{ __('Perfil') }}</x-slot:title>

    <x-account-layout active="profile" title="Meu Perfil" subtitle="Atualize seus dados e sua senha.">
        <div class="space-y-6">
            <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                <h2 class="font-urbanist text-lg font-bold text-primary">Dados Pessoais</h2>
                <div class="mt-5"><livewire:customer.profile.components.personal-information /></div>
            </div>
            <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                <h2 class="font-urbanist text-lg font-bold text-primary">Segurança</h2>
                <div class="mt-5"><livewire:customer.profile.components.change-password /></div>
            </div>
        </div>
    </x-account-layout>
</div>