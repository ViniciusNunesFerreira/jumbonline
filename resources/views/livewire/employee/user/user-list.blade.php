<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Users') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="py-6 xl:flex-auto xl:py-0">
            <div class="space-y-12">
                <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                    <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                        <div class="ml-4 mt-4">
                            <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                                {{ __('Admins') }}
                            </h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                {{ __('List of store administrators.') }}
                            </p>
                        </div>
                        @if(auth()->user()->is_admin)
                            <div class="ml-4 mt-4 flex-shrink-0">
                                <a
                                    href="{{ route('employee.settings.user.create', ['admin' => 'true']) }}"
                                    class="btn btn-primary"
                                >
                                    {{ __('Add admin') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <ul
                                role="list"
                                class="divide-y divide-slate-100 dark:divide-slate-800"
                            >
                                @foreach($this->employees->where('is_admin', true)->all() as $employee)
                                    <li class="relative flex justify-between gap-x-6 py-5">
                                        <div class="flex gap-x-4">
                                            <img
                                                class="h-12 w-12 flex-none rounded-full bg-slate-100"
                                                src="{{ $employee->getFirstMediaUrl('avatar', 'thumb') }}"
                                                alt="{{ $employee->name }}"
                                            >
                                            <div class="min-w-0 flex-auto">
                                                <p class="text-sm font-medium leading-6 text-slate-900 dark:text-slate-200">
                                                    <a
                                                        href="{{ route('employee.settings.user.detail', $employee->id) }}"
                                                        class="hover:underline"
                                                    >
                                                        {{ $employee->name }}
                                                    </a>
                                                </p>
                                                <p class="mt-1 flex text-xs leading-5 text-slate-500 dark:text-slate-400">
                                                    <a
                                                        href="mailto:{{ $employee->email }}"
                                                        class="hover:underline"
                                                    >
                                                        {{ $employee->email }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-x-4">
                                            <a
                                                href="{{ route('employee.settings.user.detail', $employee->id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                {{ __('View profile') }}
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                        <div class="ml-4 mt-4">
                            <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                                {{ __('Staffs') }}
                            </h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                {{ __('List of all staff members.') }}
                            </p>
                        </div>
                        @if(auth()->user()->is_admin)
                            <div class="ml-4 mt-4 flex-shrink-0">
                                <a
                                    href="{{ route('employee.settings.user.create') }}"
                                    class="btn btn-primary"
                                >
                                    {{ __('Add staff') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <ul
                                role="list"
                                class="divide-y divide-slate-100 dark:divide-slate-800"
                            >
                                @foreach($this->employees->where('is_admin', false)->all() as $employee)
                                    <li class="relative flex justify-between gap-x-6 py-5">
                                        <div class="flex gap-x-4">
                                            <img
                                                class="h-12 w-12 flex-none rounded-full bg-slate-100"
                                                src="{{ $employee->getFirstMediaUrl('avatar', 'thumb') }}"
                                                alt="{{ $employee->name }}"
                                            >
                                            <div class="min-w-0 flex-auto">
                                                <p class="text-sm font-medium leading-6 text-slate-900 dark:text-slate-200">
                                                    <a
                                                        href="{{ route('employee.settings.user.detail', $employee->id) }}"
                                                        class="hover:underline"
                                                    >
                                                        {{ $employee->name }}
                                                    </a>
                                                </p>
                                                <p class="mt-1 flex text-xs leading-5 text-slate-500 dark:text-slate-400">
                                                    <a
                                                        href="mailto:{{ $employee->email }}"
                                                        class="hover:underline"
                                                    >
                                                        {{ $employee->email }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-x-4">
                                            <a
                                                href="{{ route('employee.settings.user.detail', $employee->id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                {{ __('View profile') }}
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
