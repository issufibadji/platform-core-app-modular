<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Feature Flags') }}</flux:heading>
        @can('core.featureflags.create')
            <flux:button icon="plus" href="{{ route('core.featureflags.create') }}" wire:navigate>
                {{ __('New Flag') }}
            </flux:button>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="flex gap-3 mb-6">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search by key, module or description…') }}"
            icon="magnifying-glass"
            class="max-w-sm"
        />
        <flux:select wire:model.live="status" class="max-w-xs">
            <option value="">{{ __('All') }}</option>
            <option value="enabled">{{ __('Enabled') }}</option>
            <option value="disabled">{{ __('Disabled') }}</option>
        </flux:select>
    </div>

    @if($this->flags->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:icon name="bolt" class="h-8 w-8 mb-3 text-zinc-300 dark:text-zinc-600" />
            <flux:heading size="lg" class="mb-1">{{ __('No feature flags found') }}</flux:heading>
            <flux:text>{{ __('Create a flag to enable or disable platform features.') }}</flux:text>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Key') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Module') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Description') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Scope') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Status') }}</th>
                        @can('core.featureflags.update')
                            <th class="px-4 py-3 text-right font-medium">{{ __('Actions') }}</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($this->flags as $flag)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-zinc-900 dark:text-zinc-100">
                                {{ $flag->key }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $flag->module ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300 max-w-xs truncate text-xs">
                                {{ $flag->description ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($flag->organization_id)
                                    <flux:badge size="sm" color="blue">{{ __('Org') }} #{{ $flag->organization_id }}</flux:badge>
                                @else
                                    <flux:badge size="sm" color="zinc">{{ __('Global') }}</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($flag->is_enabled)
                                    <flux:badge size="sm" color="green">{{ __('Enabled') }}</flux:badge>
                                @else
                                    <flux:badge size="sm" color="red">{{ __('Disabled') }}</flux:badge>
                                @endif
                            </td>
                            @can('core.featureflags.update')
                                <td class="px-4 py-3 text-right">
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        :icon="$flag->is_enabled ? 'x-circle' : 'check-circle'"
                                        wire:click="toggle({{ $flag->id }})"
                                        wire:confirm="{{ $flag->is_enabled ? __('Disable this flag?') : __('Enable this flag?') }}"
                                    >
                                        {{ $flag->is_enabled ? __('Disable') : __('Enable') }}
                                    </flux:button>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->flags->links() }}
        </div>
    @endif
</div>
