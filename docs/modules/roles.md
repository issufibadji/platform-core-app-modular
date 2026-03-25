# Module: Roles

**Path:** `Modules/Roles/`
**Alias:** `roles`
**Priority:** 20
**Status:** ✅ Live

---

## Purpose

UI module for managing spatie roles and their permission assignments. Roles are global (not organization-scoped) in the current implementation, managed via `Spatie\Permission\Models\Role`. The module provides List and Create/Edit Livewire screens.

---

## Database Tables

All tables are owned by **spatie/laravel-permission** — the Roles module does not define its own migrations.

### `roles` (spatie)

| Column | Type | Notes |
| --- | --- | --- |
| `id` | bigint unsigned | Primary key |
| `name` | varchar(255) | Role name (e.g., `super-admin`) |
| `guard_name` | varchar(255) | Guard (`web`) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### `role_has_permissions` (spatie pivot)

| Column | Type | Notes |
| --- | --- | --- |
| `permission_id` | bigint unsigned | FK → permissions.id |
| `role_id` | bigint unsigned | FK → roles.id |

### `model_has_roles` (spatie pivot)

| Column | Type | Notes |
| --- | --- | --- |
| `role_id` | bigint unsigned | FK → roles.id |
| `model_type` | varchar | Polymorphic type |
| `model_id` | bigint unsigned | Polymorphic id |

---

## Models

Uses `Spatie\Permission\Models\Role` directly — no custom model in the module.

---

## Livewire Components

### `ListRoles` (`roles.list-roles`)

Table of all roles. Features:

- Loads roles with `Role::withCount(['permissions', 'users'])`
- Displays permission count and user count per role

**Registered in** `RolesServiceProvider::boot()`:

```php
Livewire::component('roles.list-roles', ListRoles::class);
```

### `CreateRole` (`roles.create-role`)

Create/Edit role form. Features:

- Dual-use: `mount(?Role $role)` — null for create, model for edit
- Multi-select permission checkboxes grouped by `module.resource` prefix (e.g., `core.organizations`)
- Saves via `Role::firstOrCreate()` + `$role->syncPermissions()`

---

## Routes

| Method | URI | Controller action | Route name |
| --- | --- | --- | --- |
| GET | `/core/roles` | `RolesController@index` | `core.roles.index` |
| GET | `/core/roles/create` | `RolesController@create` | `core.roles.create` |

All routes use `auth` + `verified` middleware.

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.roles.view` | View role list |
| `core.roles.create` | Create new roles |
| `core.roles.update` | Edit role permissions |
| `core.roles.delete` | Delete roles |

Seeded in `RolesAndPermissionsSeeder`.

---

## Seeded Roles

The `RolesAndPermissionsSeeder` creates these roles with the following permission sets:

| Role | Permissions |
| --- | --- |
| `super-admin` | All permissions (synced via `Permission::all()`) |
| `owner` | org: view/create/update/delete, users: view/create/update, roles: view, permissions: view, menu: view |
| `manager` | org: view/update, users: view/create/update, roles: view, permissions: view, menu: view |
| `operator` | org: view, users: view/create, menu: view |
| `viewer` | org: view, users: view, menu: view |

---

## Menu Entry

Registered in `Modules/Menu/config/menu.php`:

```php
[
    'label'      => 'Roles',
    'route'      => 'core.roles.index',
    'icon'       => 'shield-check',
    'permission' => 'core.roles.view',
    'sort'       => 1,
],
```

---

## Dependencies

- spatie/laravel-permission ^6.0 (provides `Role` model and all pivot tables)

---

## Next Improvements

- Gate checks on all Livewire methods
- Role deletion with confirmation
- Role cloning
- Organization-scoped roles (roles tied to a specific org)
