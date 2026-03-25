<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button variant="ghost" icon="arrow-left" href="{{ route('core.files.index') }}" wire:navigate />
        <flux:heading size="xl">{{ __('Upload File') }}</flux:heading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 space-y-5">
            <flux:heading size="lg">{{ __('File') }}</flux:heading>

            <flux:field>
                <flux:label for="file">{{ __('File') }} <span class="text-red-500">*</span></flux:label>
                <input
                    id="file"
                    type="file"
                    wire:model="file"
                    class="block w-full text-sm text-zinc-600 dark:text-zinc-400
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0
                           file:text-sm file:font-medium
                           file:bg-zinc-100 file:text-zinc-700
                           dark:file:bg-zinc-800 dark:file:text-zinc-300
                           hover:file:bg-zinc-200 dark:hover:file:bg-zinc-700
                           cursor-pointer"
                />
                <div wire:loading wire:target="file" class="text-xs text-zinc-400 mt-1">
                    {{ __('Uploading…') }}
                </div>
                <flux:error name="file" />
            </flux:field>

            <flux:field>
                <flux:label for="disk">{{ __('Disk') }}</flux:label>
                <flux:select id="disk" wire:model="disk">
                    <option value="local">local</option>
                    <option value="public">public</option>
                </flux:select>
                <flux:description>{{ __('Use "public" disk only for files that should be web-accessible. Run php artisan storage:link first.') }}</flux:description>
                <flux:error name="disk" />
            </flux:field>

            <flux:field>
                <flux:label for="directory">{{ __('Directory') }}</flux:label>
                <flux:input id="directory" wire:model="directory" placeholder="uploads" />
                <flux:error name="directory" />
            </flux:field>

            <flux:field>
                <flux:label for="visibility">{{ __('Visibility') }}</flux:label>
                <flux:select id="visibility" wire:model="visibility">
                    <option value="private">private</option>
                    <option value="public">public</option>
                </flux:select>
                <flux:error name="visibility" />
            </flux:field>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="ghost" href="{{ route('core.files.index') }}" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('Upload') }}</span>
                <span wire:loading>{{ __('Uploading…') }}</span>
            </flux:button>
        </div>
    </form>
</div>
