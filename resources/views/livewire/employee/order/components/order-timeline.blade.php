<div>
    <x-card>
        <x-slot:header>
            <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                {{ __('Linha do tempo') }}
            </h3>
        </x-slot:header>
        <x-slot:content>
            @php
                $timelineColors = [
                    'default' => 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300',
                    'primary' => 'bg-accent-50 text-accent-600 dark:bg-accent-500/10 dark:text-accent-400',
                    'success' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
                    'warning' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-400/10 dark:text-yellow-400',
                    'danger' => 'bg-red-50 text-red-600 dark:bg-red-400/10 dark:text-red-400',
                ];
            @endphp
            <ul class="-mb-8">
                @foreach($events as $event)
                    <li>
                        <div class="relative pb-8">
                            @unless($loop->last)
                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-white/10" aria-hidden="true"></span>
                            @endunless
                            <div class="relative flex items-start space-x-3">
                                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full ring-4 ring-white dark:ring-slate-900 {{ $timelineColors[$event['color']] }}">
                                    <x-dynamic-component :component="$event['icon']" class="h-4 w-4" />
                                </span>
                                <div class="min-w-0 flex-1 pt-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-medium text-slate-900 dark:text-slate-200">
                                            {{ $event['title'] }}
                                        </p>
                                        @if($event['amount'] !== null)
                                            <span class="flex-shrink-0 text-sm font-semibold tabular-nums text-slate-700 dark:text-slate-300">
                                                <x-money :amount="$event['amount']" />
                                            </span>
                                        @endif
                                    </div>
                                    @if($event['description'])
                                        <p class="mt-0.5 whitespace-normal text-sm text-slate-500 dark:text-slate-400">
                                            {{ $event['description'] }}
                                        </p>
                                    @endif
                                    <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">
                                        {{ $event['at']->format('d/m/Y \à\s H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </x-slot:content>
    </x-card>
</div>