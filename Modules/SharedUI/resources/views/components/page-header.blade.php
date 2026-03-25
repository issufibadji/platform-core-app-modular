@props(['title', 'description' => null, 'backRoute' => null, 'backLabel' => 'Back'])

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        @if($backRoute)
            <flux:button variant="ghost" icon="arrow-left" href="{{ route($backRoute) }}" wire:navigate />
        @endif
        <div>
            <flux:heading size="xl">{{ $title }}</flux:heading>
            @if($description)
                <flux:text class="text-zinc-500 mt-0.5">{{ $description }}</flux:text>
            @endif
        </div>
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endif
</div>
