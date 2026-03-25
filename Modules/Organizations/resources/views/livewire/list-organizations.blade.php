<x-layouts::app :title="__('Organizations')">
    <flux:main>
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">{{ __('Organizations') }}</flux:heading>
            <flux:button href="{{ route('core.organizations.create') }}" wire:navigate icon="plus">
                {{ __('New Organization') }}
            </flux:button>
        </div>

        {{-- Filters --}}
        <div class="flex gap-3 mb-6">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Search by name, slug or email…') }}"
                icon="magnifying-glass"
                class="max-w-sm"
            />

            <flux:select wire:model.live="status" class="max-w-xs">
                <option value="">{{ __('All statuses') }}</option>
                @foreach($this->statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </flux:select>
        </div>

        {{-- Table --}}
        @if($this->organizations->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
                <flux:icon.building-office-2 class="size-12 mb-4 opacity-40" />
                <flux:heading size="lg" class="mb-1">{{ __('No organizations yet') }}</flux:heading>
                <flux:text>{{ __('Create your first organization to get started.') }}</flux:text>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Name') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Slug') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Email') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Created') }}</th>
                            <th class="px-4 py-3 text-right font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($this->organizations as $org)
                            <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $org->name }}
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 font-mono text-xs">
                                    {{ $org->slug }}
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                    {{ $org->email ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <flux:badge
                                        color="{{ $org->status->color() }}"
                                        size="sm"
                                    >
                                        {{ $org->status->label() }}
                                    </flux:badge>
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                    {{ $org->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil-square"
                                            href="{{ route('core.organizations.edit', $org) }}"
                                            wire:navigate
                                        >
                                            {{ __('Edit') }}
                                        </flux:button>
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                            wire:click="confirmDelete({{ $org->id }})"
                                            class="text-red-500 hover:text-red-600"
                                        >
                                            {{ __('Delete') }}
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $this->organizations->links() }}
            </div>
        @endif

        {{-- Delete confirmation modal --}}
        <flux:modal wire:model="confirmingDelete" class="max-w-sm">
            <div class="space-y-4">
                <flux:heading>{{ __('Delete organization?') }}</flux:heading>
                <flux:text>
                    {{ __('This action will soft-delete the organization. It can be restored later.') }}
                </flux:text>
                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" wire:click="cancelDelete">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button variant="danger" wire:click="delete" wire:loading.attr="disabled">
                        {{ __('Delete') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </flux:main>
</x-layouts::app>
