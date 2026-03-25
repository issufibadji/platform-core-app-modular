<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Settings') }}</flux:heading>
    </div>

    {{-- Filters --}}
    <div class="flex gap-3 mb-6">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search by key, value or group…') }}"
            icon="magnifying-glass"
            class="max-w-sm"
        />

        <flux:select wire:model.live="group" class="max-w-xs">
            <option value="">{{ __('All groups') }}</option>
            @foreach($this->groups as $g)
                <option value="{{ $g }}">{{ $g }}</option>
            @endforeach
        </flux:select>
    </div>

    @if($this->settings->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:heading size="lg" class="mb-1">{{ __('No settings found') }}</flux:heading>
            <flux:text>{{ __('Settings are created programmatically via SettingService::set().') }}</flux:text>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Key') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Group') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Module') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Value') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Public') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($this->settings as $setting)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-zinc-900 dark:text-zinc-100">
                                {{ $setting->key }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $setting->group ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $setting->module ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge size="sm" color="zinc">{{ $setting->type }}</flux:badge>
                            </td>
                            <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300 max-w-xs truncate text-xs">
                                {{ Str::limit($setting->value, 60) }}
                            </td>
                            <td class="px-4 py-3">
                                @if($setting->is_public)
                                    <flux:badge size="sm" color="green">{{ __('Yes') }}</flux:badge>
                                @else
                                    <flux:badge size="sm" color="zinc">{{ __('No') }}</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="pencil-square"
                                    href="{{ route('core.settings.edit', $setting) }}"
                                    wire:navigate
                                >
                                    {{ __('Edit') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->settings->links() }}
        </div>
    @endif
</div>
