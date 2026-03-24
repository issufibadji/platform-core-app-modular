# Module: Roles

**Path:** `Modules/Core/Roles/` (planned)
**Alias:** `roles`
**Priority:** 30
**Status:** Planned

---

## Purpose

Defines named roles that group permissions together. Roles are scoped to an organization — each organization manages its own role assignments independently. System roles (Admin, Member, Viewer) are seeded globally and available in all organizations.

---

## Database Tables

### `roles`

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `name` | `varchar(255)` | Display name (e.g., "Administrator") |
| `slug` | `varchar(255)` | Unique per organization (e.g., `admin`) |
| `organization_id` | `bigint unsigned\|null` | Null = system-wide role |
| `is_system` | `boolean` | True = cannot be deleted |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |

### `user_role` (pivot)

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `user_id` | `bigint unsigned` | FK → users.id |
| `role_id` | `bigint unsigned` | FK → roles.id |
| `organization_id` | `bigint unsigned` | FK → organizations.id |
| `assigned_at` | `timestamp` | |

---

## Models

### `Role`

```php
namespace Modules\Core\Roles\Models;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'organization_id', 'is_system'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role')->withPivot('organization_id', 'assigned_at');
    }
}
```

---

## Routes

### Web

| Method | URI | Component | Route Name |
|--------|-----|-----------|------------|
| GET | `/roles` | `ListRoles` | `roles.index` |
| GET | `/roles/create` | `CreateRole` | `roles.create` |
| GET | `/roles/{role}/edit` | `EditRole` | `roles.edit` |

---

## Permissions

| Permission String | Description |
|-------------------|-------------|
| `core.roles.view` | View role list |
| `core.roles.create` | Create custom roles |
| `core.roles.update` | Edit role name and permissions |
| `core.roles.delete` | Delete non-system roles |
| `core.roles.assign` | Assign roles to users |
| `core.roles.revoke` | Remove roles from users |

---

## Default Seeded Roles

| Slug | Name | `is_system` | Description |
|------|------|-------------|-------------|
| `admin` | Administrator | true | Full access to all `core.*` permissions |
| `member` | Member | true | Standard access: view most, create some |
| `viewer` | Viewer | true | Read-only access |

---

## Livewire Screens

### `ListRoles`
Table of roles for the current organization, plus system roles. Columns: Name, Type (System/Custom), Permission Count, User Count.

### `CreateRole`
Form: Name, Slug. Redirects to EditRole to assign permissions.

### `EditRole`
Edit role name and assign permissions via a checkbox list grouped by module.

---

## Dependencies

- Organizations (roles are scoped to an org)
- Users (users are assigned roles)

---

## Next Improvements

- Role cloning (duplicate a role and its permissions)
- Role hierarchy (role inherits another role's permissions)
- Scoped roles (e.g., a role valid only for a specific resource)
