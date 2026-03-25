# Module: Organizations

**Path:** `Modules/Organizations/`
**Alias:** `organizations`
**Priority:** 0
**Status:** Implemented — migrations, models, Livewire CRUD, tests

---

## Purpose

The Organizations module is the foundational Core module. It provides multi-tenant context for the entire platform. Every user, setting, and resource belongs to an organization.

An organization represents a tenant account — a company, team, or independent workspace. Users can belong to multiple organizations.

---

## Files Created

### Migrations

| File | Description |
| --- | --- |
| `database/migrations/2025_01_01_000001_create_organizations_table.php` | Creates the `organizations` table |
| `database/migrations/2025_01_01_000002_create_organization_user_table.php` | Creates the `organization_user` pivot table |

### Models

| File | Namespace |
| --- | --- |
| `app/Models/Organization.php` | `Modules\Organizations\Models\Organization` |
| `app/Models/OrganizationUser.php` | `Modules\Organizations\Models\OrganizationUser` |

### Enums

| File | Namespace |
| --- | --- |
| `app/Enums/OrganizationStatus.php` | `Modules\Organizations\Enums\OrganizationStatus` |

### Services

| File | Namespace |
| --- | --- |
| `app/Services/OrganizationService.php` | `Modules\Organizations\Services\OrganizationService` |

### HTTP

| File | Namespace |
| --- | --- |
| `app/Http/Livewire/ListOrganizations.php` | `Modules\Organizations\Http\Livewire\ListOrganizations` |
| `app/Http/Livewire/CreateOrganization.php` | `Modules\Organizations\Http\Livewire\CreateOrganization` |
| `app/Http/Requests/StoreOrganizationRequest.php` | `Modules\Organizations\Http\Requests\StoreOrganizationRequest` |
| `app/Http/Requests/UpdateOrganizationRequest.php` | `Modules\Organizations\Http\Requests\UpdateOrganizationRequest` |

### Views

| File | Description |
| --- | --- |
| `resources/views/livewire/list-organizations.blade.php` | Paginated, searchable org list with delete modal |
| `resources/views/livewire/create-organization.blade.php` | Create form with auto-slug generation |

### Tests

| File | Description |
| --- | --- |
| `tests/Feature/OrganizationTest.php` | Model, service, and route tests (Pest) |

---

## Database Tables

### `organizations`

| Column | Type | Notes |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key |
| `name` | `varchar(255)` | Display name |
| `slug` | `varchar(255)` | Unique, URL-safe identifier. Auto-generated from name. |
| `status` | `varchar(50)` | `active` / `suspended` / `archived` — cast to `OrganizationStatus` enum |
| `email` | `varchar(255) null` | Contact email |
| `phone` | `varchar(50) null` | Contact phone |
| `timezone` | `varchar(100) null` | e.g. `America/New_York` |
| `locale` | `varchar(10) null` | e.g. `en` |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |
| `deleted_at` | `timestamp null` | Soft deletes |

### `organization_user` (pivot)

| Column | Type | Notes |
| --- | --- | --- |
| `id` | `bigint unsigned` | Primary key |
| `organization_id` | `bigint unsigned` | FK to `organizations.id` (cascade delete) |
| `user_id` | `bigint unsigned` | FK to `users.id` (cascade delete) |
| `is_owner` | `boolean` | True if this user owns the org |
| `status` | `varchar(50)` | Member status |
| `joined_at` | `timestamp null` | When the user joined |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |

> Unique constraint on `(organization_id, user_id)`.

---

## Routes

All routes require `auth` + `verified` middleware. Prefix: `/core/organizations`.

| Method | URI | Component | Route Name |
| --- | --- | --- | --- |
| GET | `/core/organizations` | `ListOrganizations` | `core.organizations.index` |
| GET | `/core/organizations/create` | `CreateOrganization` | `core.organizations.create` |
| GET | `/core/organizations/{organization}/edit` | `CreateOrganization` | `core.organizations.edit` |

---

## Permissions

| Permission String | Description |
| --- | --- |
| `core.organizations.view` | View organization list and details |
| `core.organizations.create` | Create a new organization |
| `core.organizations.update` | Edit an existing organization |
| `core.organizations.delete` | Delete or archive an organization |

> Permission enforcement will be wired once the Permissions module is built.

---

## Livewire Components

### `ListOrganizations`

- Paginated table of organizations (15/page)
- Real-time search by name, slug, email (`wire:model.live.debounce`)
- Status filter dropdown
- Soft-delete with confirmation modal via `flux:modal`
- Uses `#[Computed]` for reactive query

### `CreateOrganization`

- Fields: name, slug (auto-generated, editable), email, phone, timezone, locale
- Slug auto-updates as name is typed; stops auto-updating once manually edited
- Inline validation via `$this->validate()`
- On success: redirects to `core.organizations.index` with Livewire navigate

---

## Service Provider

`OrganizationsServiceProvider` extends `ModuleServiceProvider` and:

- Boots `EventServiceProvider` and `RouteServiceProvider` sub-providers
- Registers Livewire components via `Livewire::component()`

Livewire component aliases:

- `organizations::list-organizations`
- `organizations::create-organization`

---

## Dependencies

- **None** — Organizations is the foundational module.
- Requires `users` table (standard Laravel migration) before running `organization_user` migration.

---

## Next Steps

1. **EditOrganization Livewire component** — reuse create form in edit mode, bound to existing org
2. **ViewOrganization Livewire component** — read-only detail page with member list
3. **Permissions enforcement** — gate list/create/edit/delete behind `core.organizations.*` permissions once Permissions module is built
4. **Sidebar navigation entry** — add Organizations link to `resources/views/layouts/app/sidebar.blade.php`
5. **Seeder** — seed default demo organizations and wire into `CoreDatabaseSeeder`
6. **Organization factory** — `database/factories/OrganizationFactory.php` for use in tests
7. **Audit log integration** — once `AuditLog` module is built, fire audit events in `OrganizationService`
8. **Files integration** — organization logo via the `Files` module
