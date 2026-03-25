<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button variant="ghost" icon="arrow-left" href="{{ route('core.roles.index') }}" wire:navigate />
        <flux:heading size="xl">{{ __('New Role') }}</flux:heading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
            <flux:heading size="lg">{{ __('Role Details') }}</flux:heading>

            <flux:field>
                <flux:label for="name">{{ __('Name') }} <span class="text-red-500">*</span></flux:label>
                <flux:input id="name" wire:model="name" placeholder="{{ __('manager') }}" required />
                <flux:description>{{ __('Lowercase, no spaces. E.g.: super-admin, manager, viewer') }}</flux:description>
                <flux:error name="name" />
            </flux:field>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-4">
            <flux:heading size="lg">{{ __('Permissions') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ __('Select which permissions this role will have.') }}</flux:text>

            @foreach($this->permissions as $group => $perms)
                <div class="space-y-2">
                    <flux:heading size="sm" class="text-zinc-500 uppercase tracking-wide text-xs">{{ $group }}</flux:heading>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($perms as $permission)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="selectedPermissions"
                                    value="{{ $permission->name }}"
                                    class="rounded border-zinc-300"
                                />
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="ghost" href="{{ route('core.roles.index') }}" wire:navigate>{{ __('Cancel') }}</flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('Create Role') }}</span>
                <span wire:loading>{{ __('Creating…') }}</span>
            </flux:button>
        </div>
    </form>
</div>
