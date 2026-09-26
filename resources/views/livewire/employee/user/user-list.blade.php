<div>
    <x-slot:title>
        {{ __('Usuários') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Usuários') }}
                </h1>
            </div>

            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Administradores') }}</h3>
                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Têm acesso ao Financeiro e a todas as áreas do painel.') }}</p>
                        </div>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('employee.settings.user.create', ['admin' => 'true']) }}" class="btn btn-primary flex-shrink-0">
                                {{ __('Novo administrador') }}
                            </a>
                        @endif
                    </div>
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <ul role="list" class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($this->employees->where('is_admin', true) as $employee)
                            <li class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <img class="h-10 w-10 flex-none rounded-full bg-slate-100 object-cover ring-2 ring-white dark:bg-slate-800 dark:ring-slate-900" src="{{ $employee->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $employee->name }}">
                                    <div class="min-w-0">
                                        <a href="{{ route('employee.settings.user.detail', $employee->id) }}" class="truncate text-sm font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400">
                                            {{ $employee->name }}
                                        </a>
                                        <p class="truncate text-xs text-slate-400">{{ $employee->email }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('employee.settings.user.detail', $employee->id) }}" class="btn btn-sm btn-outline-primary !rounded-xl flex-shrink-0">
                                    {{ __('Ver perfil') }}
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400 sm:px-6">
                                {{ __('Nenhum administrador cadastrado.') }}
                            </li>
                        @endforelse
                    </ul>
                </x-slot:content>
            </x-card>

            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Equipe') }}</h3>
                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Funcionários com acesso ao painel, sem visibilidade do Financeiro.') }}</p>
                        </div>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('employee.settings.user.create') }}" class="btn btn-primary flex-shrink-0">
                                {{ __('Novo funcionário') }}
                            </a>
                        @endif
                    </div>
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <ul role="list" class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($this->employees->where('is_admin', false) as $employee)
                            <li class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <img class="h-10 w-10 flex-none rounded-full bg-slate-100 object-cover ring-2 ring-white dark:bg-slate-800 dark:ring-slate-900" src="{{ $employee->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $employee->name }}">
                                    <div class="min-w-0">
                                        <a href="{{ route('employee.settings.user.detail', $employee->id) }}" class="truncate text-sm font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400">
                                            {{ $employee->name }}
                                        </a>
                                        <p class="truncate text-xs text-slate-400">{{ $employee->email }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('employee.settings.user.detail', $employee->id) }}" class="btn btn-sm btn-outline-primary !rounded-xl flex-shrink-0">
                                    {{ __('Ver perfil') }}
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400 sm:px-6">
                                {{ __('Nenhum funcionário cadastrado.') }}
                            </li>
                        @endforelse
                    </ul>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</div>