@php
    $navLink = function (string $routeName, string $activePattern, string $icon, string $label) {
        $active = request()->routeIs($activePattern);
        return compact('routeName', 'active', 'icon', 'label');
    };
@endphp

<nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-6">

        {{-- Sem grupo — sempre no topo --}}
        <li>
            
                <a href="{{ route('employee.dashboard') }}"
                @class([
                    'group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-semibold transition-colors',
                    'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.dashboard'),
                    'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.dashboard'),
                ])
            >
                <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.dashboard'), 'bg-transparent' => !request()->routeIs('employee.dashboard')])></span>
                <x-heroicon-o-home @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.dashboard'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.dashboard')]) />
                {{ __('Dashboard') }}
            </a>
        </li>

        {{-- PRINCIPAL --}}
        <li>
            <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Principal') }}</div>
            <ul role="list" class="mt-2 space-y-1">
                <li>
                    
                        <a href="{{ route('employee.orders.list') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.orders.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.orders.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.orders.*'), 'bg-transparent' => !request()->routeIs('employee.orders.*')])></span>
                        <x-heroicon-o-inbox @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.orders.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.orders.*')]) />
                        {{ __('Pedidos') }}
                    </a>
                </li>
                <li>
                    
                        <a href="{{ route('employee.customers.list') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.customers.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.customers.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.customers.*'), 'bg-transparent' => !request()->routeIs('employee.customers.*')])></span>
                        <x-heroicon-o-users @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.customers.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.customers.*')]) />
                        {{ __('Clientes') }}
                    </a>
                </li>
                @can('admin')
                    <li>
                        
                            <a href="{{ route('employee.financial.dashboard') }}"
                            @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.financial.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.financial.*')])
                        >
                            <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.financial.*'), 'bg-transparent' => !request()->routeIs('employee.financial.*')])></span>
                            <x-heroicon-o-chart-bar @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.financial.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.financial.*')]) />
                            {{ __('Financeiro') }}
                        </a>
                    </li>
                @endcan
                <li>
                    
                        <a href="{{ route('employee.correios.postagem') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.correios.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.correios.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.correios.*'), 'bg-transparent' => !request()->routeIs('employee.correios.*')])></span>
                        <x-heroicon-o-envelope @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.correios.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.correios.*')]) />
                        {{ __('Correios') }}
                    </a>
                </li>
            </ul>
        </li>

        {{-- CATÁLOGO --}}
        <li>
            <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Catálogo') }}</div>
            <ul role="list" class="mt-2 space-y-1">
                <li>
                    
                        <a href="{{ route('employee.products.list') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.products.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.products.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.products.*'), 'bg-transparent' => !request()->routeIs('employee.products.*')])></span>
                        <x-heroicon-o-tag @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.products.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.products.*')]) />
                        {{ __('Produtos') }}
                    </a>
                </li>
                <li>
                    
                        <a href="{{ route('employee.categories.list') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.categories.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.categories.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.categories.*'), 'bg-transparent' => !request()->routeIs('employee.categories.*')])></span>
                        <x-heroicon-o-rectangle-stack @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.categories.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.categories.*')]) />
                        {{ __('Categorias') }}
                    </a>
                </li>
                <li>
                    
                        <a href="{{ route('employee.collections.list') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.collections.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.collections.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.collections.*'), 'bg-transparent' => !request()->routeIs('employee.collections.*')])></span>
                        <x-heroicon-o-rectangle-stack @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.collections.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.collections.*')]) />
                        {{ __('Grupos') }}
                    </a>
                </li>
                
            </ul>
        </li>

        {{-- OPERAÇÃO --}}
        <li>
            <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Operação') }}</div>
            <ul role="list" class="mt-2 space-y-1">
                @can('admin')
                    <li>
                        <a href="{{ route('employee.shipping.manager') }}"
                            @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.shipping.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.shipping.*')])
                        >
                            <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.shipping.*'), 'bg-transparent' => !request()->routeIs('employee.shipping.*')])></span>
                            <x-heroicon-o-truck @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.shipping.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.shipping.*')]) />
                            {{ __('Envio') }}
                        </a>
                    </li>
                @endcan
                
                <li>
                    
                    <a href="{{ route('employee.abandoned-carts.list') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.abandoned-carts.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.abandoned-carts.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.abandoned-carts.*'), 'bg-transparent' => !request()->routeIs('employee.abandoned-carts.*')])></span>
                        <x-heroicon-o-shopping-cart @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.abandoned-carts.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.abandoned-carts.*')]) />
                        {{ __('Carrinhos Abandonados') }}
                    </a>
                </li>

                @can('admin')
                    <li>
                        <a href="{{ route('employee.promotions.list') }}"
                            @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.promotions.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.promotions.*')])
                        >
                            <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.promotions.*'), 'bg-transparent' => !request()->routeIs('employee.promotions.*')])></span>
                            <x-heroicon-o-ticket @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.promotions.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.promotions.*')]) />
                            {{ __('Promoções Frete') }}
                        </a>
                    </li>
                @endcan

                <li>
                    
                    <a href="{{ route('employee.prison.list') }}"
                        @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.prison.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.prison.*')])
                    >
                        <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.prison.*'), 'bg-transparent' => !request()->routeIs('employee.prison.*')])></span>
                        <x-heroicon-o-building-office @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.prison.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.prison.*')]) />
                        {{ __('Unid. Prisional') }}
                    </a>
                </li>

            </ul>
        </li>

        {{-- CONTEÚDO --}}
        <li>
            <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Conteúdo') }}</div>
            <ul role="list" class="mt-2 space-y-1">

                @can('admin')
                    <li>
                        
                        <a href="{{ route('employee.articles.list') }}"
                            @class(['group relative flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => request()->routeIs('employee.articles.*'), 'text-slate-600 hover:bg-slate-50 hover:text-primary dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' => !request()->routeIs('employee.articles.*')])
                        >
                            <span @class(['absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-r-full', 'bg-accent-500' => request()->routeIs('employee.articles.*'), 'bg-transparent' => !request()->routeIs('employee.articles.*')])></span>
                            <x-heroicon-o-newspaper @class(['h-5 w-5 shrink-0', 'text-accent-600 dark:text-accent-400' => request()->routeIs('employee.articles.*'), 'text-slate-400 group-hover:text-primary dark:group-hover:text-white' => !request()->routeIs('employee.articles.*')]) />
                            {{ __('Blog') }}
                        </a>
                    </li> 
                @endcan   

            </ul>
        </li>
    </ul>

    {{-- Configurações — separada e discreta, de propósito --}}
    @can('admin')
    <div class="mt-auto border-t border-slate-100 pt-4 dark:border-white/5">
        
        <a href="{{ route('employee.settings.general') }}"
            @class(['group flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 transition-colors hover:bg-slate-50 hover:text-primary dark:text-slate-500 dark:hover:bg-white/5 dark:hover:text-white', 'bg-slate-50 text-primary dark:bg-white/5 dark:text-white' => request()->routeIs('employee.settings.*')])
        >
            <x-heroicon-o-cog-6-tooth class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-primary dark:group-hover:text-white" />
            {{ __('Configurações') }}
        </a>
    </div>
    @endcan
</nav>