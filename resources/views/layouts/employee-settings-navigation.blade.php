<aside
    wire:ignore
    class="pb-4 flex overflow-x-auto border-b border-gray-900/5 xl:block xl:w-64 xl:flex-none xl:border-0 xl:pb-0"
>
    <nav class="flex-none">
        <ul
            role="list"
            class="flex gap-x-3 gap-y-1 whitespace-nowrap xl:flex-col"
        >
            <li>
                <a
                    href="{{ route('employee.settings.general') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.general'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.general')])
                >
                    <x-heroicon-o-building-storefront @class(['h-6 w-6 shrink-0', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.general'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:bg-slate-800 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.general')]) />
                    {{ __('General') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.branding') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.branding'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.branding')])
                >
                    <x-heroicon-o-star @class(['h-6 w-6 shrink-0', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.branding'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:bg-slate-800 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.branding')]) />
                    {{ __('Brand') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.user.list') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.user.*'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.user.*')])
                >
                    <x-heroicon-o-user-circle @class(['h-6 w-6 shrink-0', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.user.*'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:bg-slate-800 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.user.*')]) />
                    {{ __('Users') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.navigation') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.navigation'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.navigation')])
                >
                    <x-heroicon-o-bars-3-bottom-left @class(['h-6 w-6 shrink-0', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.navigation'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:bg-slate-800 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.navigation')]) />
                    {{ __('Navigation') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.carousels.list') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.carousels.*'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.carousels.*')])
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"
                        @class(['h-6 w-6 shrink-0 fill-current', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.carousels.*'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:bg-slate-800 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.carousels.*')])
                    >
                        <path d="M448 128C483.3 128 512 156.7 512 192V448C512 483.3 483.3 512 448 512H64C28.65 512 0 483.3 0 448V192C0 156.7 28.65 128 64 128H448zM448 160H64C46.33 160 32 174.3 32 192V448C32 465.7 46.33 480 64 480H448C465.7 480 480 465.7 480 448V192C480 174.3 465.7 160 448 160zM448 64C456.8 64 464 71.16 464 80C464 88.84 456.8 96 448 96H64C55.16 96 48 88.84 48 80C48 71.16 55.16 64 64 64H448zM400 0C408.8 0 416 7.164 416 16C416 24.84 408.8 32 400 32H112C103.2 32 96 24.84 96 16C96 7.164 103.2 0 112 0H400z" />
                    </svg>
                    {{ __('Carousels') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.layout') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.layout'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.layout')])
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                        @class(['h-6 w-6 shrink-0 fill-current', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.layout'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:bg-slate-800 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.layout')])
                    >
                        <path d="M448 32C483.3 32 512 60.65 512 96V416C512 451.3 483.3 480 448 480H64C28.65 480 0 451.3 0 416V96C0 60.65 28.65 32 64 32H448zM448 64H64C46.33 64 32 78.33 32 96V160H480V96C480 78.33 465.7 64 448 64zM64 448H160V192H32V416C32 433.7 46.33 448 64 448zM192 448H448C465.7 448 480 433.7 480 416V192H192V448z" />
                    </svg>
                    {{ __('Layout') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.template') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.template'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.template')])
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                        @class(['h-6 w-6 shrink-0 fill-current', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.template'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.template')])
                    >
                        <path d="M176 32C202.5 32 224 53.49 224 80V176C224 202.5 202.5 224 176 224H80C53.49 224 32 202.5 32 176V80C32 53.49 53.49 32 80 32H176zM176 64H80C71.16 64 64 71.16 64 80V176C64 184.8 71.16 192 80 192H176C184.8 192 192 184.8 192 176V80C192 71.16 184.8 64 176 64zM176 288C202.5 288 224 309.5 224 336V432C224 458.5 202.5 480 176 480H80C53.49 480 32 458.5 32 432V336C32 309.5 53.49 288 80 288H176zM176 320H80C71.16 320 64 327.2 64 336V432C64 440.8 71.16 448 80 448H176C184.8 448 192 440.8 192 432V336C192 327.2 184.8 320 176 320zM288 80C288 53.49 309.5 32 336 32H432C458.5 32 480 53.49 480 80V176C480 202.5 458.5 224 432 224H336C309.5 224 288 202.5 288 176V80zM320 80V176C320 184.8 327.2 192 336 192H432C440.8 192 448 184.8 448 176V80C448 71.16 440.8 64 432 64H336C327.2 64 320 71.16 320 80zM384 272C392.8 272 400 279.2 400 288V368H480C488.8 368 496 375.2 496 384C496 392.8 488.8 400 480 400H400V480C400 488.8 392.8 496 384 496C375.2 496 368 488.8 368 480V400H288C279.2 400 272 392.8 272 384C272 375.2 279.2 368 288 368H368V288C368 279.2 375.2 272 384 272z" />
                    </svg>
                    {{ __('Template') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.payments') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.payments'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.payments')])
                >
                    <x-heroicon-o-credit-card @class(['h-6 w-6 shrink-0', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.payments'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.payments')]) />
                    {{ __('Payments') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.checkout') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.checkout'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.checkout')])
                >
                    <x-heroicon-o-shopping-cart @class(['h-6 w-6 shrink-0', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.checkout'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.checkout')]) />
                    {{ __('Checkout') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('employee.settings.license') }}"
                    @class(['group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm leading-6 font-semibold', 'bg-gray-50 text-sky-600 dark:bg-slate-800 dark:text-white' => request()->routeIs('employee.settings.license'), 'text-gray-700 hover:text-sky-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('employee.settings.license')])
                >
                    <x-heroicon-o-finger-print @class(['h-6 w-6 shrink-0', 'text-sky-600 dark:text-white' => request()->routeIs('employee.settings.license'), 'text-gray-400 group-hover:text-sky-600 dark:group-hover:text-white' => ! request()->routeIs('employee.settings.license')]) />
                    {{ __('License') }}
                </a>
            </li>
        </ul>
    </nav>
</aside>
