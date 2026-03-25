# Module: Users

**Path:** `Modules/Users/`
**Alias:** `users`
**Priority:** 10
**Status:** ✅ Live

---

## Purpose

UI module for managing `App\Models\User` records. Provides paginated list, create, and edit screens. Does not own the `users` table (that belongs to Laravel's base scaffold) but renders and mutates user data via Livewire.

---

## Database Tables

### `users` (base — owned by Laravel scaffold)

| Column | Type | Notes |
| --- | --- | --- |
| `id` | bigint unsigned | Primary key |
| `name` | varchar(255) | Full display name |
| `email` | varchar(255) | Unique |
| `email_verified_at` | timestamp\|null | |
| `password` | varchar(255) | Hashed |
| `two_factor_secret` | text\|null | Fortify 2FA |
| `two_factor_recovery_codes` | text\|null | Fortify 2FA |
| `two_factor_confirmed_at` | timestamp\|null | Fortify 2FA |
| `remember_token` | varchar(100)\|null | |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### `organization_user` (pivot — owned by Organizations module)

See [organizations.md](organizations.md) for the full pivot table definition.

### Spatie permission tables

`model_has_roles`, `model_has_permissions`, `role_has_permissions` — owned by spatie/laravel-permission. The Users module reads roles from these tables via `$user->getRoleNames()`.

---

## Model

`App\Models\User` (base — not inside the module). Extended with:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    // ...
}
```

The Users module does not define its own `User` model — it uses the base `App\Models\User` directly.

---

## Livewire Components

### `ListUsers` (`users.list-users`)

Paginated table of all users. Features:

- Search by name or email (`#[Url]` property)
- Displays spatie roles via `getRoleNames()` (guards against missing trait)
- Pagination with `WithPagination`

**Registered in** `UsersServiceProvider::boot()`:

```php
Livewire::component('users.list-users', ListUsers::class);
```

### `CreateUser` (`users.create-user`)

Create and edit form. Features:

- Dual-use: `mount(?User $user)` — null for create, model for edit
- Fields: name, email, password, optional role, optional organization
- Validation: unique email rule skips current user on edit
- Saves via `App\Models\User::create()` / `$user->update()`
- Role assignment via `$user->syncRoles()`

---

## Routes

| Method | URI | Controller action | Route name |
| --- | --- | --- | --- |
| GET | `/core/users` | `UsersController@index` | `core.users.index` |
| GET | `/core/users/create` | `UsersController@create` | `core.users.create` |
| GET | `/core/users/{user}/edit` | `UsersController@edit` | `core.users.edit` |

All routes use `auth` + `verified` middleware.

The controller returns Blade page views (`users::index`, `users::create`, `users::edit`) which embed the Livewire components.

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.users.view` | View user list |
| `core.users.create` | Create users |
| `core.users.update` | Edit user details |
| `core.users.delete` | Delete or deactivate users |

Seeded in `RolesAndPermissionsSeeder`. Gate checks should be added to Livewire component methods in future iterations.

---

## Menu Entry

Registered in `Modules/Menu/config/menu.php`:

```php
[
    'label'      => 'Users',
    'route'      => 'core.users.index',
    'icon'       => 'users',
    'permission' => 'core.users.view',
    'sort'       => 2,
],
```

---

## Dependencies

- `App\Models\User` (base Laravel scaffold)
- Organizations module (`organization_user` pivot for org assignment)
- spatie/laravel-permission (role display and assignment)

---

## Next Improvements

- Gate checks on all Livewire methods (`$this->authorize('core.users.create')`)
- User deactivation (soft delete or `is_active` flag)
- User avatar upload via Files module
- Last seen / activity tracking
- Bulk import via CSV
