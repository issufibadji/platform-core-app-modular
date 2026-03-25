<div>
    {{-- Header --}}
    <div class="mb-8">
        <flux:heading size="xl">{{ __('Welcome back') }}, {{ auth()->user()->name }}</flux:heading>
        <flux:text class="text-zinc-500 dark:text-zinc-400 mt-1">
            {{ __('Platform Core — overview of your workspace.') }}
        </flux:text>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Organizations --}}
        <a href="{{ route('core.organizations.index') }}"
           class="flex items-center gap-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                <flux:icon name="building-office-2" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
            </div>
            <div>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $organizationsCount }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Organizations') }}</p>
            </div>
        </a>

        {{-- Users --}}
        <a href="{{ route('core.users.index') }}"
           class="flex items-center gap-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-violet-50 dark:bg-violet-900/20">
                <flux:icon name="users" class="h-6 w-6 text-violet-600 dark:text-violet-400" />
            </div>
            <div>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $usersCount }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Users') }}</p>
            </div>
        </a>

        {{-- Unread Notifications --}}
        <a href="{{ route('core.notifications.index') }}"
           class="flex items-center gap-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20">
                <flux:icon name="bell" class="h-6 w-6 text-amber-600 dark:text-amber-400" />
            </div>
            <div>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $unreadNotifications }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Unread Notifications') }}</p>
            </div>
        </a>

        {{-- Active Feature Flags --}}
        <a href="{{ route('core.featureflags.index') }}"
           class="flex items-center gap-4 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20">
                <flux:icon name="bolt" class="h-6 w-6 text-green-600 dark:text-green-400" />
            </div>
            <div>
                <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $featureFlagsCount }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Active Feature Flags') }}</p>
            </div>
        </a>
    </div>

    {{-- Quick links + Recent activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Quick Links --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6">
            <flux:heading size="lg" class="mb-4">{{ __('Quick Links') }}</flux:heading>
            <div class="grid grid-cols-2 gap-3">
                @foreach([
                    ['label' => 'Roles',        'route' => 'core.roles.index',        'icon' => 'shield-check',           'perm' => 'core.roles.view'],
                    ['label' => 'Permissions',   'route' => 'core.permissions.index',  'icon' => 'key',                    'perm' => 'core.permissions.view'],
                    ['label' => 'Settings',      'route' => 'core.settings.index',     'icon' => 'cog-6-tooth',            'perm' => 'core.settings.view'],
                    ['label' => 'Audit Log',     'route' => 'core.auditlog.index',     'icon' => 'clipboard-document-list','perm' => 'core.auditlog.view'],
                    ['label' => 'Files',         'route' => 'core.files.index',        'icon' => 'paper-clip',             'perm' => 'core.files.view'],
                    ['label' => 'Feature Flags', 'route' => 'core.featureflags.index', 'icon' => 'bolt',                   'perm' => 'core.featureflags.view'],
                ] as $link)
                    @can($link['perm'])
                        <a href="{{ route($link['route']) }}"
                           class="flex items-center gap-2 rounded-lg border border-zinc-100 dark:border-zinc-800 px-4 py-3 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <flux:icon :name="$link['icon']" class="h-4 w-4 text-zinc-400 dark:text-zinc-500" />
                            {{ __($link['label']) }}
                        </a>
                    @endcan
                @endforeach
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="lg">{{ __('Recent Activity') }}</flux:heading>
                @can('core.auditlog.view')
                    <flux:button size="sm" variant="ghost" href="{{ route('core.auditlog.index') }}" wire:navigate>
                        {{ __('View all') }}
                    </flux:button>
                @endcan
            </div>

            @if($recentAuditCount > 0)
                <div class="flex items-center gap-3 text-zinc-600 dark:text-zinc-400">
                    <flux:icon name="clipboard-document-list" class="h-5 w-5 text-zinc-400" />
                    <flux:text>
                        {{ $recentAuditCount }} {{ __('audit event(s) in the last 7 days.') }}
                    </flux:text>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <flux:icon name="clipboard-document-list" class="h-8 w-8 text-zinc-300 dark:text-zinc-600 mb-2" />
                    <flux:text class="text-zinc-400 dark:text-zinc-500 text-sm">
                        {{ __('No recent activity.') }}
                    </flux:text>
                </div>
            @endif
        </div>
    </div>
</div>
