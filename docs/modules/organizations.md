# Module: Organizations

**Part of:** Platform Core
**Path:** `Modules/Organizations/`
**Namespace:** `Modules\Organizations\`
**Module alias:** `organizations`
**Priority:** 10 (loads after Core at priority 0)

---

## Purpose

Provides multi-tenant organization context for the platform. Every future app family (ScheduleHub, Stocky, Workforce, etc.) can scope its data and users to an organization. This module manages organization creation, user membership, and ownership assignment.

---

## Database Tables

### `organizations`

| Column       | Type         | Notes                        |
|--------------|--------------|------------------------------|
| `id`         | bigint PK    |                              |
| `name`       | string       | Required                     |
| `slug`       | string       | Unique, auto-generated       |
| `status`     | string       | `active`, `inactive`, `suspended` |
| `email`      | string       | Nullable                     |
| `phone`      | string       | Nullable                     |
| `timezone`   | string       | Nullable                     |
| `locale`     | string       | Nullable                     |
| `created_at` | timestamp    |                              |
| `updated_at` | timestamp    |                              |
| `deleted_at` | timestamp    | Soft deletes                 |

### `organization_user` (pivot)

| Column            | Type      | Notes                        |
|-------------------|-----------|------------------------------|
| `id`              | bigint PK |                              |
| `organization_id` | bigint FK | → `organizations.id`         |
| `user_id`         | bigint FK | → `users.id`                 |
| `is_owner`        | boolean   | Default: false               |
| `status`          | string    | Default: `active`            |
| `joined_at`       | timestamp | Nullable                     |
| `created_at`      | timestamp |                              |
| `updated_at`      | timestamp |                              |

Unique constraint: `(organization_id, user_id)`.

---

## Migrations

| File                                                                    | Description                     |
|-------------------------------------------------------------------------|---------------------------------|
| `2026_01_01_000001_create_organizations_table.php`                      | Creates `organizations` table   |
| `2026_01_01_000002_create_organization_user_table.php`                  | Creates `organization_user` pivot |

---

## Models

### `Modules\Organizations\Models\Organization`

- Extends `Illuminate\Database\Eloquent\Model`
- Uses `SoftDeletes`
- Casts `status` → `OrganizationStatus` enum
- Auto-generates `slug` from `name` on create (via `booted()`)
- Relations:
  - `users()` — `BelongsToMany` App\Models\User via `organization_user`
  - `owners()` — filtered `BelongsToMany` where `is_owner = true`

### `Modules\Organizations\Models\OrganizationUser`

- Extends `Illuminate\Database\Eloquent\Relations\Pivot`
- Represents a single membership record
- Relations:
  - `organization()` → `BelongsTo Organization`
  - `user()` → `BelongsTo User`

---

## Enums

### `Modules\Organizations\Enums\OrganizationStatus`

| Case        | Value       | Label       | Color  |
|-------------|-------------|-------------|--------|
| `Active`    | `active`    | Active      | green  |
| `Inactive`  | `inactive`  | Inactive    | zinc   |
| `Suspended` | `suspended` | Suspended   | red    |

---

## Services

### `Modules\Organizations\Services\OrganizationService`

| Method                                    | Description                          |
|-------------------------------------------|--------------------------------------|
| `paginate(int $perPage, string $search)`  | Paginated list with optional search  |
| `create(array $data)`                     | Create a new organization            |
| `update(Organization, array $data)`       | Update an existing organization      |
| `delete(Organization)`                    | Soft-delete an organization          |

---

## Livewire Components

| Class                                                    | Route                       |
|----------------------------------------------------------|-----------------------------|
| `Modules\Organizations\Http\Livewire\OrganizationIndex`  | `GET /core/organizations`   |
| `Modules\Organizations\Http\Livewire\OrganizationCreate` | `GET /core/organizations/create` |

Both components use `#[Layout('layouts.app')]` to render inside the root Flux sidebar layout.

---

## Routes

All routes are registered in `Modules/Organizations/routes/web.php` under middleware `['auth', 'verified']`.

| Name                          | Method | URI                          | Component           |
|-------------------------------|--------|------------------------------|---------------------|
| `core.organizations.index`    | GET    | `/core/organizations`        | OrganizationIndex   |
| `core.organizations.create`   | GET    | `/core/organizations/create` | OrganizationCreate  |

---

## Permissions (planned)

The following permission slugs are reserved for future policy/gate enforcement:

| Permission                   | Description                    |
|------------------------------|--------------------------------|
| `core.organizations.view`    | List and view organizations    |
| `core.organizations.create`  | Create new organizations       |
| `core.organizations.update`  | Update existing organizations  |
| `core.organizations.delete`  | Soft-delete organizations      |

These will be wired up once the `Roles` and `Permissions` modules are implemented.

---

## Files Created

```
Modules/Organizations/
  app/
    Enums/
      OrganizationStatus.php
    Http/
      Livewire/
        OrganizationIndex.php
        OrganizationCreate.php
      Requests/
        StoreOrganizationRequest.php
    Models/
      Organization.php
      OrganizationUser.php
    Providers/
      OrganizationsServiceProvider.php   (updated)
    Services/
      OrganizationService.php
  database/
    migrations/
      2026_01_01_000001_create_organizations_table.php
      2026_01_01_000002_create_organization_user_table.php
  resources/
    views/
      livewire/
        organization-index.blade.php
        organization-create.blade.php
  routes/
    web.php                              (updated)
  module.json                            (updated)
```

---

## Dependencies

- **Laravel 13** — framework
- **Livewire 4** — reactive components
- **Livewire Flux** — UI component library (used in views)
- **`nwidart/laravel-modules`** — module loader
- **`App\Models\User`** — pivot relationship target

---

## Next Steps

1. **Users module** — add `organizations()` relationship to User model; allow viewing memberships
2. **Roles & Permissions modules** — enforce `core.organizations.*` permission gates via policies
3. **OrganizationEdit Livewire component** — edit name, status, contact, and locale
4. **OrganizationShow Livewire component** — view members, ownership, and metadata
5. **Sidebar navigation** — add "Organizations" link to `resources/views/layouts/app/sidebar.blade.php`
6. **Factory & Seeder** — add `OrganizationFactory` for testing and `OrganizationsDatabaseSeeder`
7. **Feature tests** — test CRUD flows and validation in `Modules/Organizations/tests/Feature/`
