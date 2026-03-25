<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button variant="ghost" icon="arrow-left" href="{{ route('core.users.index') }}" wire:navigate />
        <flux:heading size="xl">{{ $user ? __('Edit User') : __('New User') }}</flux:heading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
            <flux:heading size="lg">{{ __('Account') }}</flux:heading>

            <flux:field>
                <flux:label for="name">{{ __('Name') }} <span class="text-red-500">*</span></flux:label>
                <flux:input id="name" wire:model="name" placeholder="{{ __('John Doe') }}" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label for="email">{{ __('Email') }} <span class="text-red-500">*</span></flux:label>
                <flux:input id="email" type="email" wire:model="email" placeholder="{{ __('john@example.com') }}" required />
                <flux:error name="email" />
            </flux:field>

            @if(!$user)
                <flux:field>
                    <flux:label for="password">{{ __('Password') }} <span class="text-red-500">*</span></flux:label>
                    <flux:input id="password" type="password" wire:model="password" />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label for="password_confirmation">{{ __('Confirm Password') }}</flux:label>
                    <flux:input id="password_confirmation" type="password" wire:model="password_confirmation" />
                </flux:field>
            @endif
        </div>

        @if(!$user)
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
                <flux:heading size="lg">{{ __('Organization') }}</flux:heading>

                <flux:field>
                    <flux:label for="organization_id">{{ __('Assign to Organization') }}</flux:label>
                    <flux:select id="organization_id" wire:model="organization_id">
                        <option value="">{{ __('None') }}</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:description>{{ __('Optional. User can be assigned to an organization later.') }}</flux:description>
                </flux:field>
            </div>
        @endif

        <div class="flex justify-end gap-3">
            <flux:button variant="ghost" href="{{ route('core.users.index') }}" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $user ? __('Save Changes') : __('Create User') }}</span>
                <span wire:loading>{{ __('Saving…') }}</span>
            </flux:button>
        </div>
    </form>
</div>
