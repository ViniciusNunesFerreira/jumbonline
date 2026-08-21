@props(['brandSettings', 'size' => 'md', 'variant' => 'default'])

@php
    $sizes = [
        'sm' => ['img' => 'h-9', 'text' => 'text-lg'],
        'md' => ['img' => 'h-11', 'text' => 'text-2xl'],
        'lg' => ['img' => 'h-14', 'text' => 'text-3xl'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
    $textColor = $variant === 'light' ? 'text-white' : 'text-primary';
@endphp

<a href="/" class="inline-flex items-center gap-2">
    @if($brandSettings->logo_path)
        <img src="{{ Storage::url($brandSettings->logo_path) }}" alt="Jumbonline" class="{{ $s['img'] }} w-auto">
    @else
        <img src="{{ asset('img/mascote-logo-mark.png') }}" alt="" aria-hidden="true" class="{{ $s['img'] }} w-auto object-contain">
        <span class="font-urbanist {{ $s['text'] }} font-black leading-none tracking-tight {{ $textColor }}">
            Jumbo<span class="text-accent">nline</span>
        </span>
    @endif
</a>