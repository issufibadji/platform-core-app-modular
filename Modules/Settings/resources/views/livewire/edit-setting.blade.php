<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button variant="ghost" icon="arrow-left" href="{{ route('core.settings.index') }}" wire:navigate />
        <flux:heading size="xl">{{ __('Edit Setting') }}</flux:heading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
            <flux:heading size="lg">{{ __('Setting') }}</flux:heading>

            <flux:field>
                <flux:label>{{ __('Key') }}</flux:label>
                <flux:input value="{{ $setting->key }}" disabled />
            </flux:field>

            <flux:field>
                <flux:label for="value">{{ __('Value') }}</flux:label>
                <flux:textarea id="value" wire:model="value" rows="4" />
                <flux:error name="value" />
            </flux:field>

            <flux:field>
                <flux:label for="type">{{ __('Type') }}</flux:label>
                <flux:select id="type" wire:model="type">
                    <option value="string">string</option>
                    <option value="boolean">boolean</option>
                    <option value="integer">integer</option>
                    <option value="float">float</option>
                    <option value="json">json</option>
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label for="group">{{ __('Group') }}</flux:label>
                <flux:input id="group" wire:model="group" placeholder="{{ __('e.g. email, security') }}" />
                <flux:error name="group" />
            </flux:field>

            <flux:field>
                <flux:checkbox id="is_public" wire:model="is_public" />
                <flux:label for="is_public">{{ __('Expose as public setting') }}</flux:label>
                <flux:description>{{ __('Public settings can be read without authentication.') }}</flux:description>
            </flux:field>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="ghost" href="{{ route('core.settings.index') }}" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('Save Changes') }}</span>
                <span wire:loading>{{ __('Saving…') }}</span>
            </flux:button>
        </div>
    </form>
</div>
