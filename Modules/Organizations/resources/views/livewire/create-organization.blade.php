<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button
            variant="ghost"
            icon="arrow-left"
            href="{{ route('core.organizations.index') }}"
            wire:navigate
        />
        <flux:heading size="xl">
            {{ $organization ? __('Edit Organization') : __('New Organization') }}
        </flux:heading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
            <flux:heading size="lg">{{ __('Basic Information') }}</flux:heading>

            <flux:field>
                <flux:label for="name">{{ __('Name') }} <span class="text-red-500">*</span></flux:label>
                <flux:input
                    id="name"
                    wire:model.live.debounce.300ms="name"
                    placeholder="{{ __('Acme Corporation') }}"
                    required
                />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label for="slug">{{ __('Slug') }}</flux:label>
                <flux:input
                    id="slug"
                    wire:model.live="slug"
                    placeholder="{{ __('acme-corporation') }}"
                    prefix="/"
                />
                <flux:description>{{ __('URL-safe identifier. Auto-generated from name if left empty.') }}</flux:description>
                <flux:error name="slug" />
            </flux:field>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
            <flux:heading size="lg">{{ __('Contact') }}</flux:heading>

            <flux:field>
                <flux:label for="email">{{ __('Email') }}</flux:label>
                <flux:input
                    id="email"
                    type="email"
                    wire:model="email"
                    placeholder="{{ __('contact@acme.com') }}"
                />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label for="phone">{{ __('Phone') }}</flux:label>
                <flux:input
                    id="phone"
                    wire:model="phone"
                    placeholder="{{ __('+1 555 000 0000') }}"
                />
                <flux:error name="phone" />
            </flux:field>
        </div>

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
            <flux:heading size="lg">{{ __('Locale & Timezone') }}</flux:heading>

            <flux:field>
                <flux:label for="timezone">{{ __('Timezone') }}</flux:label>
                <flux:input
                    id="timezone"
                    wire:model="timezone"
                    placeholder="{{ __('America/New_York') }}"
                />
                <flux:error name="timezone" />
            </flux:field>

            <flux:field>
                <flux:label for="locale">{{ __('Locale') }}</flux:label>
                <flux:input
                    id="locale"
                    wire:model="locale"
                    placeholder="{{ __('en') }}"
                />
                <flux:error name="locale" />
            </flux:field>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button
                variant="ghost"
                href="{{ route('core.organizations.index') }}"
                wire:navigate
            >
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    {{ $organization ? __('Save Changes') : __('Create Organization') }}
                </span>
                <span wire:loading>{{ __('Saving…') }}</span>
            </flux:button>
        </div>
    </form>
</div>
