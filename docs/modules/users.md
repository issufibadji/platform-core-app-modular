# Module: Users

**Path:** `Modules/Core/Users/` (planned — base users table is in `database/migrations/`)
**Alias:** `users`
**Priority:** 20
**Status:** Planned (base `users` table exists from Laravel scaffold)

---

## Purpose

Manages user accounts and their membership within organizations. Extends the base Laravel `users` table with organization membership, two-factor authentication, and profile management.

The base `users` table is created by Laravel's default migrations. The Users module extends it with organization associations, profile data, and UI screens.

---

## Database Tables

### `users` (base — owned by Laravel scaffold)

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `name` | `varchar(255)` | Full display name |
| `email` | `varchar(255)` | Unique |
| `email_verified_at` | `timestamp\|null` | |
| `password` | `varchar(255)` | Hashed |
| `two_factor_secret` | `text\|null` | Fortify 2FA |
| `two_factor_recovery_codes` | `text\|null` | Fortify 2FA |
| `two_factor_confirmed_at` | `timestamp\|null` | Fortify 2FA |
| `remember_token` | `varchar(100)\|null` | |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |

### `organization_user` (pivot — owned by Organizations module)

See [organizations.md](organizations.md) for the full pivot table definition.

---

## Models

### `User` (base — `app/Models/User.php`)

The root `User` model is in `app/Models/`. The Users module extends behavior via traits and relationships rather than replacing the model.

**Key relationships:**
```php
public function organizations(): BelongsToMany
{
    return $this->belongsToMany(Organization::class)->withPivot('is_owner', 'joined_at');
}

public function roles(): HasMany
{
    return $this->hasMany(UserRole::class);
}
```

---

## Routes

### Web (`routes/web.php`)

| Method | URI | Livewire Component | Route Name |
|--------|-----|--------------------|------------|
| GET | `/users` | `ListUsers` | `users.index` |
| GET | `/users/invite` | `InviteUser` | `users.invite` |
| GET | `/users/{user}` | `ViewUser` | `users.show` |
| GET | `/users/{user}/edit` | `EditUser` | `users.edit` |

---

## Permissions

| Permission String | Description |
|-------------------|-------------|
| `core.users.view` | View user list and profiles |
| `core.users.create` | Create users directly |
| `core.users.update` | Edit user details |
| `core.users.delete` | Deactivate or delete users |
| `core.users.invite` | Send email invitations to new users |

---

## Livewire Screens

### `ListUsers`
Paginated table of users in the active organization. Columns: Name, Email, Status, Joined At. Actions: Edit, Deactivate.

### `InviteUser`
Form to invite a user by email. Sends invitation email with a time-limited token.

### `EditUser`
Edit a user's name, email, and role within the current organization.

---

## Dependencies

- Organizations module (for `organization_user` pivot)
- Laravel Fortify (provides 2FA columns and auth actions)

---

## Next Improvements

- User avatar upload via Files module
- Last seen / activity tracking
- Bulk user import via CSV
- User export
