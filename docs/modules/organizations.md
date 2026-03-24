# Module: Organizations

**Path:** `Modules/Organizations/`
**Alias:** `organizations`
**Priority:** 10
**Status:** In progress (scaffold exists, implementation pending)

---

## Purpose

The Organizations module provides the multi-tenancy foundation for the entire platform. Every user, setting, file, and resource belongs to an organization. It is the first module to implement and the one everything else depends on.

An organization represents a tenant account — a company, team, or independent workspace. Users can belong to multiple organizations with different roles in each.

---

## Database Tables

### `organizations`

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `name` | `varchar(255)` | Display name of the organization |
| `slug` | `varchar(255)` | Unique, URL-safe identifier (e.g., `acme-corp`) |
| `domain` | `varchar(255)\|null` | Optional custom domain for subdomain routing |
| `status` | `varchar(50)` | Enum: `active`, `suspended`, `archived` |
| `settings` | `json\|null` | Organization-level settings overrides |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |
| `deleted_at` | `timestamp\|null` | Soft deletes |

### `organization_user` (pivot)

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `organization_id` | `bigint unsigned` | FK → organizations.id |
| `user_id` | `bigint unsigned` | FK → users.id |
| `is_owner` | `boolean` | True if this user created/owns the org |
| `joined_at` | `timestamp` | When the user joined this organization |

> Roles are assigned via the Roles module's `user_role` table, scoped to an organization.

---

## Models

### `Organization`

```php
namespace Modules\Organizations\Models;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'domain', 'status', 'settings'];

    protected $casts = [
        'status' => OrganizationStatus::class,
        'settings' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('is_owner', 'joined_at');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', OrganizationStatus::Active);
    }
}
```

**Enum: `OrganizationStatus`**

```php
enum OrganizationStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';
}
```

---

## Routes

### Web (`routes/web.php`)

| Method | URI | Livewire Component | Route Name |
|--------|-----|--------------------|------------|
| GET | `/organizations` | `ListOrganizations` | `organizations.index` |
| GET | `/organizations/create` | `CreateOrganization` | `organizations.create` |
| GET | `/organizations/{organization}` | `ViewOrganization` | `organizations.show` |
| GET | `/organizations/{organization}/edit` | `EditOrganization` | `organizations.edit` |

All routes require `auth` and `verified` middleware.

### API (`routes/api.php`)

| Method | URI | Controller Action | Route Name |
|--------|-----|-------------------|------------|
| GET | `/api/v1/organizations` | `index` | `api.organizations.index` |
| POST | `/api/v1/organizations` | `store` | `api.organizations.store` |
| GET | `/api/v1/organizations/{id}` | `show` | `api.organizations.show` |
| PUT | `/api/v1/organizations/{id}` | `update` | `api.organizations.update` |
| DELETE | `/api/v1/organizations/{id}` | `destroy` | `api.organizations.destroy` |

All API routes require `auth:sanctum` middleware.

---

## Permissions

| Permission String | Description |
|-------------------|-------------|
| `core.organizations.view` | View organization list and details |
| `core.organizations.create` | Create a new organization |
| `core.organizations.update` | Edit an existing organization |
| `core.organizations.delete` | Delete or archive an organization |
| `core.organizations.suspend` | Suspend an organization (disable access) |
| `core.organizations.manage_members` | Add or remove users from an organization |

These are seeded in the Organizations module's seeder and assignable to roles via the Permissions module.

---

## Livewire Screens

### `ListOrganizations`

- Shows a paginated, searchable table of organizations
- Columns: Name, Slug, Domain, Status, Member Count, Created At
- Actions: View, Edit, Suspend, Archive
- Filter: by status (Active / Suspended / Archived)
- Empty state if no organizations exist

### `CreateOrganization`

- Form: Name, Slug (auto-generated from name, editable), Domain (optional)
- Slug uniqueness validation (real-time via Livewire)
- On submit: dispatches `OrganizationCreated` event, redirects to index

### `EditOrganization`

- Pre-filled form with current organization data
- Same fields as Create plus Status selector
- On submit: dispatches `OrganizationUpdated` event

### `ViewOrganization`

- Read-only details panel: all fields + member list
- Action buttons: Edit, Suspend/Unsuspend, Archive
- Confirmation modal before destructive actions

---

## Actions

| Class | Description |
|-------|-------------|
| `CreateOrganizationAction` | Validates slug uniqueness, creates org, fires `OrganizationCreated` |
| `UpdateOrganizationAction` | Updates fields, fires `OrganizationUpdated` |
| `SuspendOrganizationAction` | Sets status to `suspended`, fires `OrganizationSuspended` |
| `ArchiveOrganizationAction` | Soft-deletes, fires `OrganizationArchived` |

---

## Events

| Event | Fired when |
|-------|-----------|
| `OrganizationCreated` | A new organization is saved |
| `OrganizationUpdated` | Organization fields are changed |
| `OrganizationSuspended` | Status changes to `suspended` |
| `OrganizationArchived` | Organization is soft-deleted |

---

## Dependencies

- **None** — Organizations is the foundational module.
- The `users` table (from base Laravel migrations) must exist before the `organization_user` pivot.

---

## Service Provider Registration

```php
// Modules/Organizations/Providers/OrganizationsServiceProvider.php

public function boot(): void
{
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'organizations');
    $this->loadTranslationsFrom(__DIR__ . '/../lang', 'organizations');

    Gate::policy(Organization::class, OrganizationPolicy::class);
}
```

---

## Next Improvements

- **Subdomain routing middleware:** Resolve the active organization from the subdomain automatically and bind it to the request.
- **Organization invitations:** Allow inviting users to an organization by email before they have an account.
- **Ownership transfer:** Let an owner transfer ownership to another member.
- **Organization logos:** Use the Files module to attach a logo to each organization.
- **Audit log integration:** All create/update/delete actions on `Organization` should produce `AuditEntry` records via the `Auditable` trait once the AuditLog module is built.
- **FeatureFlag scoping:** Once FeatureFlags is built, Organization should support per-org flag overrides.
