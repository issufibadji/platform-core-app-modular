<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Files') }}</flux:heading>
        <flux:button href="{{ route('core.files.upload') }}" wire:navigate icon="arrow-up-tray">
            {{ __('Upload File') }}
        </flux:button>
    </div>

    {{-- Search --}}
    <div class="flex gap-3 mb-6">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search by filename, type…') }}"
            icon="magnifying-glass"
            class="max-w-sm"
        />
    </div>

    @if($this->files->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:heading size="lg" class="mb-1">{{ __('No files yet') }}</flux:heading>
            <flux:text>{{ __('Upload your first file to get started.') }}</flux:text>
            <div class="mt-4">
                <flux:button href="{{ route('core.files.upload') }}" wire:navigate icon="arrow-up-tray">
                    {{ __('Upload File') }}
                </flux:button>
            </div>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Name') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Size') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Disk') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Visibility') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Uploaded by') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('When') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($this->files as $file)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100 max-w-xs truncate">
                                {{ $file->original_name }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 font-mono text-xs">
                                {{ $file->extension ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $file->humanSize() }}
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge size="sm" color="zinc">{{ $file->disk }}</flux:badge>
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge size="sm" color="{{ $file->visibility === 'public' ? 'green' : 'zinc' }}">
                                    {{ $file->visibility }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $file->uploader?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs whitespace-nowrap">
                                {{ $file->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    wire:click="confirmDelete({{ $file->id }})"
                                    class="text-red-500 hover:text-red-600"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $this->files->links() }}
        </div>
    @endif

    {{-- Delete modal --}}
    <flux:modal wire:model="confirmingDelete" class="max-w-sm">
        <div class="space-y-4">
            <flux:heading>{{ __('Delete file?') }}</flux:heading>
            <flux:text>{{ __('This will permanently delete the file from storage.') }}</flux:text>
            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="cancelDelete">{{ __('Cancel') }}</flux:button>
                <flux:button variant="danger" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
