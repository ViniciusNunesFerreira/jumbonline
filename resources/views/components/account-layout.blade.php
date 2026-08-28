@props(['active', 'title' => null, 'subtitle' => null])

<div class="bg-complement-500 py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        @isset($header)
            {{ $header }}
        @else
            <h1 class="font-urbanist text-2xl font-bold text-primary sm:text-3xl">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        @endisset

        <div class="mt-6">
            <x-account-nav :active="$active" />
        </div>

        <div class="mt-8">
            {{ $slot }}
        </div>
    </div>
</div>