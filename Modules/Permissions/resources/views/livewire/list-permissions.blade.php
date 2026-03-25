<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Permissions') }}</flux:heading>
    </div>

    <div class="flex gap-3 mb-6">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search permissions…') }}"
            icon="magnifying-glass"
            class="max-w-sm"
        />
    </div>

    @forelse($this->permissionGroups as $module => $permissions)
        <div class="mb-6">
            <flux:heading size="sm" class="mb-3 uppercase tracking-wide text-xs text-zinc-500">
                {{ $module }}
            </flux:heading>
            <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($permissions as $permission)
                            <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="px-4 py-2.5 font-mono text-xs text-zinc-700 dark:text-zinc-300">
                                    {{ $permission->name }}
                                </td>
                                <td class="px-4 py-2.5 text-right text-zinc-400 text-xs">
                                    {{ $permission->guard_name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:heading size="lg" class="mb-1">{{ __('No permissions found') }}</flux:heading>
            <flux:text>{{ __('Run the seeder to create initial permissions.') }}</flux:text>
            <div class="mt-3">
                <flux:badge color="zinc" size="sm">php artisan db:seed --class=RolesAndPermissionsSeeder</flux:badge>
            </div>
        </div>
    @endforelse
</div>
