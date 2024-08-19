@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-base md:text-xl text-primary dark:text-slate-200']) }}>
    {{ $value ?? $slot }}
</label>
