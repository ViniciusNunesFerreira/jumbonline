<div>
    <x-slot:title>
        {{ __('Meu Perfil') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                {{ __('Meu Perfil') }}
            </h1>
        </div>

        <div class="mt-6 space-y-6 ">
            <livewire:employee.profile.components.personal-information />

            <livewire:employee.profile.components.change-password />
        </div>
    </div>
</div>