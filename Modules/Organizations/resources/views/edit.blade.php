<x-layouts::app :title="__('Edit Organization')">
    <flux:main>
        <livewire:organizations.create-organization :organization="$organization" />
    </flux:main>
</x-layouts::app>
