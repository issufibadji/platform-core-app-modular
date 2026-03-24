# Module: Permissions

**Path:** `Modules/Core/Permissions/` (planned)
**Alias:** `permissions`
**Priority:** 40
**Status:** Planned

---

## Purpose

Defines all platform permissions and provides the mechanism to assign them to roles. Integrates with Laravel's `Gate` so that `$user->can('core.organizations.view')` works everywhere.

Permissions are seeded — they are not user-created. Each module registers its own permissions during boot. The Permissions module assembles them into the `permissions` table and makes them assignable.

---

## Database Tables

### `permissions`

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `name` | `varchar(255)` | Unique string key: `core.organizations.view` |
| `description` | `varchar(255)\|null` | Human-readable description |
| `module` | `varchar(100)` | Which module owns this permission |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |

### `role_permission` (pivot)

| Column | Type | Notes |
|--------|------|-------|
| `role_id` | `bigint unsigned` | FK → roles.id |
| `permission_id` | `bigint unsigned` | FK → permissions.id |

---

## Models

### `Permission`

```php
namespace Modules\Core\Permissions\Models;

class Permission extends Model
{
    protected $fillable = ['name', 'description', 'module'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
```

---

## Gate Integration

The Permissions module registers a Gate `before` check that resolves permissions via the `permissions` table:

```php
Gate::before(function (User $user, string $ability) {
    // Superadmin bypass
    if ($user->isSuperAdmin()) return true;

    return $user->roles()
        ->whereHas('permissions', fn ($q) => $q->where('name', $ability))
        ->exists();
});
```

Usage anywhere in the app:

```php
$user->can('core.organizations.create');
Gate::authorize('core.users.delete', $user);
// In Blade:
@can('core.roles.assign') ... @endcan
// In Livewire:
$this->authorize('core.settings.manage');
```

---

## Permission Registration

Each module registers its permissions via a seeder or service provider:

```php
// In a module's seeder
$permissions = [
    ['name' => 'core.organizations.view',   'module' => 'organizations'],
    ['name' => 'core.organizations.create', 'module' => 'organizations'],
    ['name' => 'core.organizations.update', 'module' => 'organizations'],
    ['name' => 'core.organizations.delete', 'module' => 'organizations'],
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission['name']], $permission);
}
```

---

## All Core Permissions

See [naming-conventions.md](../naming-conventions.md#permissions) for the full list of `core.*` permission strings.

---

## Routes

### Web

| Method | URI | Component | Route Name |
|--------|-----|-----------|------------|
| GET | `/permissions` | `ListPermissions` | `permissions.index` |

Permissions are not directly editable via UI — they are seeded and assigned to roles via the Roles module.

---

## Dependencies

- Roles module (permissions are assigned to roles)

---

## Next Improvements

- Permission groups / categories for better UI organization in the role editor
- Direct user-level permission overrides (override a role's default for a specific user)
- Wildcard permission matching (e.g., `core.organizations.*`)
