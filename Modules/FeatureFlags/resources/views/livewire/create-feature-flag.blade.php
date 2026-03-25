<div>
    <div class="flex items-center gap-3 mb-6">
        <flux:button variant="ghost" icon="arrow-left" href="{{ route('core.featureflags.index') }}" wire:navigate>
            {{ __('Back') }}
        </flux:button>
        <flux:heading size="xl">{{ __('New Feature Flag') }}</flux:heading>
    </div>

    <form wire:submit="save" class="space-y-6 max-w-2xl">

        {{-- Key --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-4">
            <flux:heading size="lg">{{ __('Flag Identity') }}</flux:heading>

            <flux:field>
                <flux:label for="key">{{ __('Key') }}</flux:label>
                <flux:input
                    id="key"
                    wire:model="key"
                    placeholder="e.g. new-dashboard-ui"
                    class="font-mono"
                />
                <flux:error name="key" />
                <flux:description>{{ __('Lowercase letters, numbers, dots, dashes and underscores only.') }}</flux:description>
            </flux:field>

            <flux:field>
                <flux:label for="module">{{ __('Module') }}</flux:label>
                <flux:input id="module" wire:model="module" placeholder="e.g. Dashboard" />
                <flux:error name="module" />
                <flux:description>{{ __('Optional. Associates this flag with a specific module.') }}</flux:description>
            </flux:field>

            <flux:field>
                <flux:label for="description">{{ __('Description') }}</flux:label>
                <flux:textarea id="description" wire:model="description" rows="2"
                    placeholder="{{ __('What does this flag control?') }}" />
                <flux:error name="description" />
            </flux:field>
        </div>

        {{-- State --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6">
            <flux:heading size="lg" class="mb-4">{{ __('Initial State') }}</flux:heading>

            <flux:field>
                <div class="flex items-center gap-3">
                    <flux:checkbox id="is_enabled" wire:model="is_enabled" />
                    <flux:label for="is_enabled">{{ __('Enable immediately') }}</flux:label>
                </div>
                <flux:error name="is_enabled" />
            </flux:field>
        </div>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">
                {{ __('Create Flag') }}
            </flux:button>
            <flux:button variant="ghost" href="{{ route('core.featureflags.index') }}" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>
        </div>
    </form>
</div>
