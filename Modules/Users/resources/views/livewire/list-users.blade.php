<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">{{ __('Users') }}</flux:heading>
        <flux:button href="{{ route('core.users.create') }}" wire:navigate icon="plus">
            {{ __('New User') }}
        </flux:button>
    </div>

    <div class="flex gap-3 mb-6">
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('Search by name or email…') }}"
            icon="magnifying-glass"
            class="max-w-sm"
        />
    </div>

    @if($this->users->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:heading size="lg" class="mb-1">{{ __('No users found') }}</flux:heading>
            <flux:text>{{ __('Create the first platform user to get started.') }}</flux:text>
            <div class="mt-4">
                <flux:button href="{{ route('core.users.create') }}" wire:navigate icon="plus">
                    {{ __('New User') }}
                </flux:button>
            </div>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Name') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Email') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Roles') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('Joined') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($this->users as $user)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">
                                <div class="flex items-center gap-3">
                                    <flux:avatar :name="$user->name" :initials="$user->initials()" size="sm" />
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3">
                                @if(method_exists($user, 'getRoleNames'))
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->getRoleNames() as $role)
                                            <flux:badge size="sm" color="blue">{{ $role }}</flux:badge>
                                        @empty
                                            <span class="text-zinc-400 text-xs">{{ __('No roles') }}</span>
                                        @endforelse
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 text-xs">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="pencil-square"
                                    href="{{ route('core.users.edit', $user) }}"
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
        <div class="mt-4">{{ $this->users->links() }}</div>
    @endif
</div>
