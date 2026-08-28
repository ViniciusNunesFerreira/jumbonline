@props(['active' => null])

<div class="flex gap-2 overflow-x-auto border-b border-secondary">
    @foreach([
        'dashboard' => ['label' => 'Visão Geral', 'route' => 'customer.dashboard', 'icon' => 'home'],
        'orders' => ['label' => 'Meus Pedidos', 'route' => 'customer.orders.list', 'icon' => 'shopping-bag'],
        'profile' => ['label' => 'Meu Perfil', 'route' => 'customer.profile', 'icon' => 'user'],
    ] as $key => $item)
        
        <a  href="{{ route($item['route']) }}"
            @class([
                'flex items-center gap-2 whitespace-nowrap border-b-2 px-4 py-3 text-sm font-semibold transition-colors',
                'border-accent text-accent' => $active === $key,
                'border-transparent text-slate-500 hover:text-primary' => $active !== $key,
            ])
        >
            @switch($item['icon'])
                @case('home') <x-heroicon-s-home class="h-4 w-4" /> @break
                @case('shopping-bag') <x-heroicon-s-shopping-bag class="h-4 w-4" /> @break
                @case('user') <x-heroicon-s-user class="h-4 w-4" /> @break
            @endswitch
            {{ $item['label'] }}
        </a>
    @endforeach
</div>