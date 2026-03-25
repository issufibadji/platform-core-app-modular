@props(['title', 'description' => null, 'icon' => 'inbox'])

<div class="flex flex-col items-center justify-center py-16 text-center text-zinc-500 dark:text-zinc-400">
    <flux:heading size="lg" class="mb-1">{{ $title }}</flux:heading>
    @if($description)
        <flux:text>{{ $description }}</flux:text>
    @endif
    @if(isset($actions))
        <div class="mt-4">{{ $actions }}</div>
    @endif
</div>
