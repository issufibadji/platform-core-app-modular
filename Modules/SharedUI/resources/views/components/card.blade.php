@props(['title' => null, 'padding' => 'p-6'])

<div {{ $attributes->class(['rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900', $padding]) }}>
    @if($title)
        <flux:heading size="lg" class="mb-5">{{ $title }}</flux:heading>
    @endif
    {{ $slot }}
</div>
