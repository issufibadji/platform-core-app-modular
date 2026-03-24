<div>
    <flux:main>
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">Organizations</flux:heading>
            <flux:button href="{{ route('core.organizations.create') }}" wire:navigate variant="primary" icon="plus">
                New Organization
            </flux:button>
        </div>

        @if (session('success'))
            <flux:callout variant="success" icon="check-circle" class="mb-6">
                {{ session('success') }}
            </flux:callout>
        @endif

        <flux:card>
            <div class="mb-4">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search organizations..."
                    icon="magnifying-glass"
                    clearable
                />
            </div>

            @if ($organizations->isEmpty())
                <div class="py-12 text-center">
                    <flux:icon name="building-office" class="mx-auto size-12 text-zinc-400" />
                    <flux:heading class="mt-2">No organizations found</flux:heading>
                    <flux:text class="mt-1">
                        @if ($search)
                            No results for "{{ $search }}". Try a different search.
                        @else
                            Get started by creating your first organization.
                        @endif
                    </flux:text>
                    @unless ($search)
                        <div class="mt-4">
                            <flux:button href="{{ route('core.organizations.create') }}" wire:navigate variant="primary" icon="plus">
                                New Organization
                            </flux:button>
                        </div>
                    @endunless
                </div>
            @else
                <flux:table>
                    <flux:columns>
                        <flux:column>Name</flux:column>
                        <flux:column>Slug</flux:column>
                        <flux:column>Status</flux:column>
                        <flux:column>Email</flux:column>
                        <flux:column>Created</flux:column>
                    </flux:columns>

                    <flux:rows>
                        @foreach ($organizations as $organization)
                            <flux:row>
                                <flux:cell class="font-medium">
                                    {{ $organization->name }}
                                </flux:cell>
                                <flux:cell>
                                    <code class="text-sm text-zinc-500">{{ $organization->slug }}</code>
                                </flux:cell>
                                <flux:cell>
                                    <flux:badge
                                        variant="pill"
                                        color="{{ $organization->status->color() }}"
                                    >
                                        {{ $organization->status->label() }}
                                    </flux:badge>
                                </flux:cell>
                                <flux:cell>
                                    {{ $organization->email ?? '—' }}
                                </flux:cell>
                                <flux:cell>
                                    {{ $organization->created_at->toDateString() }}
                                </flux:cell>
                            </flux:row>
                        @endforeach
                    </flux:rows>
                </flux:table>

                <div class="mt-4">
                    {{ $organizations->links() }}
                </div>
            @endif
        </flux:card>
    </flux:main>
</div>
