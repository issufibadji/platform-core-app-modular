<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Audit Log') }}</flux:heading>
    </div>

    {{-- Filters --}}
    <div class="flex gap-3 mb-6">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search by event, model, or IP…') }}"
            icon="magnifying-glass"
            class="max-w-sm"
        />

        <flux:select wire:model.live="event" class="max-w-xs">
            <option value="">{{ __('All events') }}</option>
            @foreach($this->events as $e)
                <option value="{{ $e }}">{{ $e }}</option>
            @endforeach
        </flux:select>
    </div>

    @if($this->logs->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:heading size="lg" class="mb-1">{{ __('No audit entries yet') }}</flux:heading>
            <flux:text>{{ __('Audit entries are created when platform actions are performed.') }}</flux:text>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">{{ __('When') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('User') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Event') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Model') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('ID') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('IP') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($this->logs as $log)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs whitespace-nowrap">
                                {{ $log->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300 text-xs">
                                {{ $log->user?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $color = match($log->event) {
                                        'created' => 'green',
                                        'updated' => 'blue',
                                        'deleted' => 'red',
                                        default   => 'zinc',
                                    };
                                @endphp
                                <flux:badge size="sm" color="{{ $color }}">{{ $log->event }}</flux:badge>
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 font-mono text-xs">
                                {{ class_basename($log->auditable_type ?? '') ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $log->auditable_id ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 font-mono text-xs">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->logs->links() }}
        </div>
    @endif
</div>
