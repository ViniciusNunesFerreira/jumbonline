<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Profile') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="space-y-12">
            <livewire:employee.profile.components.personal-information />

            <livewire:employee.profile.components.change-password />
        </div>
    </div>
</div>
