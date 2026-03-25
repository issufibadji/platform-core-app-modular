# Module: Permissions

**Path:** `Modules/Permissions/`
**Alias:** `permissions`
**Priority:** 21
**Status:** ✅ Live

---

## Purpose

Read-only UI for browsing all platform permissions, grouped by module. Permissions are not created via this UI — they are seeded by `RolesAndPermissionsSeeder`. The Permissions module exposes them for inspection and makes them assignable through the Roles module UI.

---

## Database Tables

All tables are owned by **spatie/laravel-permission** — the Permissions module has no migrations.

### `permissions` (spatie)

| Column | Type | Notes |
| --- | --- | --- |
| `id` | bigint unsigned | Primary key |
| `name` | varchar(255) | Unique string key: `core.organizations.view` |
| `guard_name` | varchar(255) | Guard (`web`) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

## Models

Uses `Spatie\Permission\Models\Permission` directly — no custom model.

---

## Livewire Components

### `ListPermissions` (`permissions.list-permissions`)

Groups all permissions by the first segment of their name (the module prefix).

**Example grouping:**

```text
core (15 permissions)
  ├── core.organizations.view
  ├── core.organizations.create
  ├── core.organizations.update
  ├── core.organizations.delete
  ├── core.users.view
  │   ...
  └── core.menu.view
```

**Registered in** `PermissionsServiceProvider::boot()`:

```php
Livewire::component('permissions.list-permissions', ListPermissions::class);
```

---

## Routes

| Method | URI | Controller action | Route name |
| --- | --- | --- | --- |
| GET | `/core/permissions` | `PermissionsController@index` | `core.permissions.index` |

All routes use `auth` + `verified` middleware.

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.permissions.view` | View permission list |
| `core.permissions.create` | Create new permissions (seeder only) |

---

## All Seeded Permissions

The following 15 permissions are created by `RolesAndPermissionsSeeder`:

| Permission | Scope |
| --- | --- |
| `core.organizations.view` | Organizations |
| `core.organizations.create` | Organizations |
| `core.organizations.update` | Organizations |
| `core.organizations.delete` | Organizations |
| `core.users.view` | Users |
| `core.users.create` | Users |
| `core.users.update` | Users |
| `core.users.delete` | Users |
| `core.roles.view` | Roles |
| `core.roles.create` | Roles |
| `core.roles.update` | Roles |
| `core.roles.delete` | Roles |
| `core.permissions.view` | Permissions |
| `core.permissions.create` | Permissions |
| `core.menu.view` | Menu |

---

## Gate Integration

`App\Models\User` has `use HasRoles` from spatie, which registers a Gate `before` hook. Checks work everywhere:

```php
$user->can('core.organizations.create');

// In Blade:
@can('core.roles.view') ... @endcan

// In Livewire:
$this->authorize('core.users.delete');
```

---

## Menu Entry

Registered in `Modules/Menu/config/menu.php`:

```php
[
    'label'      => 'Permissions',
    'route'      => 'core.permissions.index',
    'icon'       => 'key',
    'permission' => 'core.permissions.view',
    'sort'       => 2,
],
```

---

## Dependencies

- spatie/laravel-permission ^6.0

---

## Next Improvements

- Gate checks on Livewire methods
- Permission creation UI (for app modules to register new permissions at runtime)
- Direct user-level permission overrides (bypassing role)
- Wildcard permission matching (`core.organizations.*`)
