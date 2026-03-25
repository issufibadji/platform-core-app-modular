<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <flux:heading size="xl">{{ __('Notifications') }}</flux:heading>
            @if($this->unreadCount > 0)
                <flux:badge color="blue">{{ $this->unreadCount }} {{ __('unread') }}</flux:badge>
            @endif
        </div>

        <div class="flex items-center gap-3">
            @if($this->unreadCount > 0)
                <flux:button variant="ghost" wire:click="markAllRead" size="sm">
                    {{ __('Mark all as read') }}
                </flux:button>
            @endif
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex gap-3 mb-6">
        <label class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400 cursor-pointer">
            <flux:checkbox wire:model.live="unreadOnly" />
            {{ __('Unread only') }}
        </label>
    </div>

    @if($this->notifications->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
            <flux:heading size="lg" class="mb-1">{{ __('No notifications') }}</flux:heading>
            <flux:text>{{ __("You're all caught up!") }}</flux:text>
        </div>
    @else
        <div class="space-y-2">
            @foreach($this->notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data     = $notification->data;
                @endphp
                <div class="flex items-start gap-4 rounded-xl border p-4 transition-colors
                    {{ $isUnread
                        ? 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20'
                        : 'border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900' }}">

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $data['title'] ?? $notification->type }}
                        </p>
                        @if(!empty($data['body']))
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $data['body'] }}
                            </p>
                        @endif
                        <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($isUnread)
                        <flux:button
                            size="sm"
                            variant="ghost"
                            wire:click="markRead('{{ $notification->id }}')"
                        >
                            {{ __('Mark read') }}
                        </flux:button>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $this->notifications->links() }}
        </div>
    @endif
</div>
