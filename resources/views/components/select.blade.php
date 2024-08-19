@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'block w-full h-16 rounded-md border-slate-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 dark:border-white/10 dark:bg-white/5 dark:focus:border-sky-500 dark:focus:ring-sky-500 dark:text-slate-300 dark:focus:ring-offset-slate-900']) }}>
    {{ $slot }}
</select>
