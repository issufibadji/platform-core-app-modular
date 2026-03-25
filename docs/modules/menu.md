# Module: Menu

**Path:** `Modules/Menu/`
**Alias:** `menu`
**Priority:** 30
**Status:** ✅ Live

---

## Purpose

Provides a config-driven navigation menu filtered by user permissions. The sidebar reads `MenuService::forUser()` to show only the groups and items the current user is allowed to see, based on spatie permission checks.

---

## No Database Tables

The Menu module is entirely config-driven. There are no migrations. Menu items are defined in `config/menu.php` and filtered at runtime.

---

## Config Structure

**`Modules/Menu/config/menu.php`** defines an array of groups, each with an array of items:

```php
return [
    [
        'group' => 'Platform',
        'items' => [
            [
                'label'      => 'Dashboard',
                'route'      => 'dashboard',
                'icon'       => 'home',
                'permission' => null,       // null = always visible
                'sort'       => 1,
            ],
        ],
    ],
    [
        'group' => 'Core',
        'items' => [
            ['label' => 'Organizations', 'route' => 'core.organizations.index', 'icon' => 'building-office-2', 'permission' => 'core.organizations.view', 'sort' => 1],
            ['label' => 'Users',         'route' => 'core.users.index',         'icon' => 'users',             'permission' => 'core.users.view',         'sort' => 2],
        ],
    ],
    [
        'group' => 'Access Control',
        'items' => [
            ['label' => 'Roles',       'route' => 'core.roles.index',       'icon' => 'shield-check', 'permission' => 'core.roles.view',       'sort' => 1],
            ['label' => 'Permissions', 'route' => 'core.permissions.index', 'icon' => 'key',          'permission' => 'core.permissions.view', 'sort' => 2],
        ],
    ],
];
```

---

## MenuService

**`Modules/Menu/app/Services/MenuService.php`**

```php
class MenuService
{
    public function forUser(): array
    {
        $groups = config('menu', []);

        return array_values(
            array_filter(
                array_map(function (array $group) {
                    $group['items'] = array_values(
                        array_filter($group['items'], fn ($item) => $this->canSee($item))
                    );
                    return $group;
                }, $groups),
                fn ($group) => ! empty($group['items'])
            )
        );
    }

    protected function canSee(array $item): bool
    {
        if (! auth()->check()) return false;
        if ($item['permission'] === null) return true;

        try {
            return auth()->user()->can($item['permission']);
        } catch (\Throwable) {
            return true; // graceful fallback when spatie not yet configured
        }
    }
}
```

Groups with no visible items are removed entirely. `permission: null` items are always shown.

---

## Service Provider

`MenuServiceProvider` registers `MenuService` as a singleton and merges the config:

```php
public function register(): void
{
    $this->app->singleton(MenuService::class);
    $this->mergeConfigFrom(__DIR__ . '/../../config/menu.php', 'menu');
}
```

---

## Sidebar Integration

`resources/views/layouts/app/sidebar.blade.php` calls `MenuService` directly:

```blade
@php
    $menuGroups = app(\Modules\Menu\Services\MenuService::class)->forUser();
@endphp

@foreach($menuGroups as $group)
    <flux:sidebar.group :heading="__($group['group'])" class="grid">
        @foreach($group['items'] as $item)
            <flux:sidebar.item
                :icon="$item['icon']"
                :href="route($item['route'])"
                :current="request()->routeIs($item['route'] . '*')"
                wire:navigate
            >{{ __($item['label']) }}</flux:sidebar.item>
        @endforeach
    </flux:sidebar.group>
@endforeach
```

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.menu.view` | Required to see menu items (all roles have this) |

---

## Dependencies

- spatie/laravel-permission (for `$user->can()` checks)

---

## Next Improvements

- Module-registered menu items (modules add their own items at boot instead of editing the central config)
- Database-backed items with drag-and-drop reordering in admin UI
- Nested/hierarchical menu groups
- Per-organization menu customization
- Breadcrumb generation from active menu path
