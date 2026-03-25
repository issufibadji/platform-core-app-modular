<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Roles') }}</flux:heading>
        <flux:button href="{{ route('core.roles.create') }}" wire:navigate icon="plus">
            {{ __('New Role') }}
        </flux:button>
    </div>

    @if($this->roles->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:heading size="lg" class="mb-1">{{ __('No roles yet') }}</flux:heading>
            <flux:text>{{ __('Run the seeder to create default platform roles.') }}</flux:text>
            <div class="mt-4">
                <flux:badge color="zinc" size="sm">php artisan db:seed --class=RolesAndPermissionsSeeder</flux:badge>
            </div>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Role') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Permissions') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Users') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($this->roles as $role)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                <flux:badge color="blue" size="sm">{{ $role->name }}</flux:badge>
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                {{ $role->permissions_count }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                {{ $role->users_count }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <flux:button size="sm" variant="ghost" icon="eye">
                                    {{ __('View') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
