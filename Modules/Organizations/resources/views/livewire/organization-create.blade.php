<div>
    <flux:main>
        <div class="flex items-center gap-4 mb-6">
            <flux:button href="{{ route('core.organizations.index') }}" wire:navigate variant="ghost" icon="arrow-left">
                Back
            </flux:button>
            <flux:heading size="xl">Create Organization</flux:heading>
        </div>

        <flux:card class="max-w-2xl">
            <form wire:submit="save" class="space-y-6">
                <flux:field>
                    <flux:label for="name">Name <flux:required /></flux:label>
                    <flux:input
                        id="name"
                        wire:model="name"
                        placeholder="Acme Corp"
                        autofocus
                    />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label for="slug">Slug</flux:label>
                    <flux:input
                        id="slug"
                        wire:model="slug"
                        placeholder="acme-corp (auto-generated if empty)"
                    />
                    <flux:description>URL-friendly identifier. Leave blank to auto-generate from name.</flux:description>
                    <flux:error name="slug" />
                </flux:field>

                <flux:field>
                    <flux:label for="status">Status <flux:required /></flux:label>
                    <flux:select id="status" wire:model="status">
                        @foreach ($statuses as $statusOption)
                            <flux:select.option value="{{ $statusOption->value }}">
                                {{ $statusOption->label() }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>

                <flux:separator />

                <flux:heading size="sm">Contact Information</flux:heading>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <flux:field>
                        <flux:label for="email">Email</flux:label>
                        <flux:input
                            id="email"
                            type="email"
                            wire:model="email"
                            placeholder="contact@example.com"
                        />
                        <flux:error name="email" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="phone">Phone</flux:label>
                        <flux:input
                            id="phone"
                            wire:model="phone"
                            placeholder="+1 555 000 0000"
                        />
                        <flux:error name="phone" />
                    </flux:field>
                </div>

                <flux:separator />

                <flux:heading size="sm">Locale Settings</flux:heading>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <flux:field>
                        <flux:label for="timezone">Timezone</flux:label>
                        <flux:input
                            id="timezone"
                            wire:model="timezone"
                            placeholder="America/New_York"
                        />
                        <flux:error name="timezone" />
                    </flux:field>

                    <flux:field>
                        <flux:label for="locale">Locale</flux:label>
                        <flux:input
                            id="locale"
                            wire:model="locale"
                            placeholder="en"
                        />
                        <flux:error name="locale" />
                    </flux:field>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <flux:button type="submit" variant="primary">
                        Create Organization
                    </flux:button>
                    <flux:button href="{{ route('core.organizations.index') }}" wire:navigate variant="ghost">
                        Cancel
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </flux:main>
</div>
