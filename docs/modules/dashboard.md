# Dashboard Module

## Purpose

Provides the platform home page with a summary of key metrics, quick navigation links, and recent activity for authenticated users.

## Location

`Modules/Dashboard/`

## Module Details

| Property | Value |
|---|---|
| Name | `Dashboard` |
| Alias | `dashboard` |
| Priority | `2` (loads early) |
| Provider | `Modules\Dashboard\Providers\DashboardServiceProvider` |

## Files Created

```
Modules/Dashboard/
├── app/
│   ├── Http/
│   │   ├── Controllers/DashboardController.php
│   │   └── Livewire/DashboardIndex.php
│   └── Providers/
│       ├── DashboardServiceProvider.php
│       ├── EventServiceProvider.php
│       └── RouteServiceProvider.php
├── composer.json
├── module.json
├── resources/
│   └── views/
│       ├── index.blade.php
│       └── livewire/dashboard-index.blade.php
└── routes/
    └── web.php
```

## Route

| Name | URL | Method |
|---|---|---|
| `core.dashboard.index` | `/core/dashboard` | GET |

## Permission

| Permission | Description |
|---|---|
| `core.dashboard.view` | View the dashboard page |

Granted by default to: `super-admin`, `owner`, `manager`, `operator`, `viewer`.

## Livewire Component

**`DashboardIndex`** — registered as `dashboard.dashboard-index`

### Summary Cards

Each card safely resolves data with a `class_exists()` guard so the dashboard never crashes if a module is absent:

| Card | Source | Fallback |
|---|---|---|
| Organizations count | `Organization::count()` | `0` |
| Users count | `User::count()` | `0` |
| Unread notifications | `$user->unreadNotifications()->count()` | `0` |
| Active feature flags | `FeatureFlag::where('is_enabled', true)->count()` | `0` |
| Recent audit events | `AuditEntry::where('created_at', '>=', now()->subDays(7))->count()` | `0` |

### Quick Links

Renders permission-aware links to:
- Roles, Permissions
- Settings, Feature Flags
- Audit Log, Files

Uses `@can` so links are hidden when the user lacks the required permission.

### Recent Activity section

Shows the 7-day audit count and links to the Audit Log if the user has `core.auditlog.view`.

## Menu Integration

Dashboard is listed under the **Platform** group in `Modules/Menu/config/menu.php`:

```php
[
    'label'      => 'Dashboard',
    'route'      => 'core.dashboard.index',
    'icon'       => 'home',
    'permission' => 'core.dashboard.view',
    'sort'       => 1,
],
```

## Limitations / Next Steps

- Summary cards could be made reactive (auto-refresh on a polling interval)
- Widget system could be made pluggable (other modules register widgets via service provider)
- Organization context (current org selector) not yet implemented
- No dedicated dashboard widget tests yet
