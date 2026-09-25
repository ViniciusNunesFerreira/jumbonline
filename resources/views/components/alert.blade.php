@props(['type' => 'success', 'title' => '', 'message' => ''])

@php
    $icons = [
        'success' => 'heroicon-m-check-circle',
        'error' => 'heroicon-m-exclamation-circle',
        'warning' => 'heroicon-m-exclamation-triangle',
        'info' => 'heroicon-m-information-circle',
    ][$type ?? 'success'];
@endphp

@if($message)
    <div {{ $attributes->merge() }}>
        <div @class(['rounded-xl p-4 border', 'bg-green-50 border-green-200 dark:border-green-900 dark:bg-green-900/20' => $type == 'success', 'bg-red-50 border-red-200 dark:border-red-900 dark:bg-red-900/20' => $type == 'error', 'bg-yellow-50 border-yellow-200 dark:border-yellow-900 dark:bg-yellow-900/20' => $type == 'warning', 'bg-accent-50 border-accent-200 dark:border-accent-900 dark:bg-accent-900/20' => $type == 'info'])>
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-icon
                        :name="$icons"
                        @class(['h-5 w-5', 'text-green-400' => $type == 'success', 'text-red-400' => $type == 'error', 'text-yellow-400' => $type == 'warning', 'text-accent-400' => $type == 'info'])
                    />
                </div>
                <div class="ml-3">
                    @if($title)
                        <h3 @class(['text-sm font-semibold mb-1', 'text-green-800 dark:text-white' => $type == 'success', 'text-red-800 dark:text-white' => $type == 'error', 'text-yellow-800 dark:text-white' => $type == 'warning', 'text-accent-800 dark:text-white' => $type == 'info'])>
                            {{ $title }}
                        </h3>
                    @endif
                    <div @class(['text-sm', 'font-medium' => empty($title), 'text-green-700 dark:text-white' => $type == 'success', 'text-red-700 dark:text-white' => $type == 'error', 'text-yellow-700 dark:text-white' => $type == 'warning', 'text-accent-700 dark:text-white' => $type == 'info'])>
                        {{ $message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif