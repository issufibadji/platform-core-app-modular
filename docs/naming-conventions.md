# Naming Conventions

Consistent naming across modules prevents collisions, makes the codebase searchable, and allows tooling (permissions middleware, settings resolvers, route helpers) to work predictably.

## Namespaces

Module code uses a `Modules\[Name]\` root namespace, matching the PSR-4 autoload defined in the module's `composer.json`.

```
Modules/Core/                  → Modules\Core\
Modules/Organizations/         → Modules\Organizations\
Modules/ScheduleHub/Schedules/ → Modules\ScheduleHub\Schedules\
```

Full example:

```php
namespace Modules\Organizations\Models;

class Organization extends Model { ... }
```

## Modules

Module names are **PascalCase**, single English nouns or noun phrases. The alias in `module.json` is **lowercase snake_case**.

| Module name | Alias |
|-------------|-------|
| `Organizations` | `organizations` |
| `Users` | `users` |
| `AuditLog` | `audit_log` |
| `FeatureFlags` | `feature_flags` |
| `SharedUI` | `shared_ui` |

## Models

Model class names are **PascalCase singular nouns** matching the table name's singular form.

| Model | Table |
|-------|-------|
| `Organization` | `organizations` |
| `User` | `users` |
| `Role` | `roles` |
| `Permission` | `permissions` |
| `MenuItem` | `menu_items` |
| `AuditEntry` | `audit_entries` |
| `FeatureFlag` | `feature_flags` |

## Migrations

Migration filenames follow: `YYYY_MM_DD_HHMMSS_[verb]_[table]_table.php`

```
2025_01_01_000000_create_organizations_table.php
2025_01_10_000000_add_domain_to_organizations_table.php
2025_02_01_000000_create_organization_user_table.php  # pivot tables: alphabetical order
```

## Services

Service class names are **PascalCase** with a `Service` suffix. Named after the domain concept they serve.

```php
OrganizationService
UserInvitationService
PermissionSyncService
NotificationDispatchService
```

## Actions

Action class names are **PascalCase verb phrases** with an `Action` suffix.

```php
CreateOrganizationAction
UpdateOrganizationAction
SuspendOrganizationAction
AssignRoleToUserAction
RevokePermissionAction
```

## DTOs

DTO class names describe the data shape with a `DTO` suffix.

```php
CreateOrganizationDTO
UpdateUserProfileDTO
InviteUserDTO
```

## Enums

Enum class names are **PascalCase nouns**. Backed enum values are **lowercase strings**.

```php
enum OrganizationStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
}

enum UserRole: string
{
    case Admin = 'admin';
    case Member = 'member';
}
```

## Livewire Components

Livewire class names are **PascalCase verb+noun or noun** patterns. They live under `app/Http/Livewire/[Feature]/`.

```php
// Class
Modules\Organizations\Http\Livewire\Organizations\ListOrganizations
Modules\Organizations\Http\Livewire\Organizations\CreateOrganization
Modules\Organizations\Http\Livewire\Organizations\EditOrganization

// Blade view (auto-resolved by Livewire)
organizations::livewire.organizations.list-organizations
```

Route-to-component registration (Full-Page Livewire):

```php
Route::get('/organizations', ListOrganizations::class)->name('organizations.index');
```

## Routes

Route names use **dot-separated** `[module].[resource].[action]` format.

```
organizations.index
organizations.create
organizations.edit
organizations.store
organizations.update
organizations.destroy

users.index
users.show
roles.assign
```

API route names are prefixed with `api.`:

```
api.organizations.index
api.users.show
```

## Permissions

Permission strings follow: `[scope].[resource].[action]`

**Core scope permissions:**

```
core.organizations.view
core.organizations.create
core.organizations.update
core.organizations.delete

core.users.view
core.users.create
core.users.update
core.users.delete
core.users.invite

core.roles.view
core.roles.assign
core.roles.revoke

core.permissions.manage

core.menu.manage
core.settings.manage
core.audit_log.view
core.feature_flags.manage
core.notifications.manage
core.files.upload
core.files.delete
```

**App-scoped permissions** (for future app families):

```
schedulehub.schedules.view
schedulehub.bookings.manage
stocky.inventory.view
```

Permissions are seeded into the `permissions` table and stored as strings. Use Laravel's Gate or Spatie-compatible checks:

```php
$user->can('core.organizations.create');
Gate::authorize('core.organizations.delete', $organization);
```

## Settings Keys

Settings keys follow: `[scope].[key]` or `[scope].[group].[key]`

```
app.name
app.logo
app.timezone
app.locale

notifications.email.enabled
notifications.email.from_name
notifications.slack.webhook_url

organizations.allow_subdomains
organizations.require_domain_verification

auth.two_factor.enabled
auth.session.lifetime

feature_flags.beta_ui.enabled
```

Settings are stored in the `settings` table with a `key` column matching this pattern. Resolved via a `Settings` facade or helper:

```php
setting('app.name');
setting('notifications.email.enabled', default: true);
```

## Database Columns

Use **snake_case** for all column names. Boolean columns are prefixed with `is_` or `has_`.

```sql
is_active
is_verified
has_two_factor
created_by_user_id
organization_id
```

## Events

Event class names are **past-tense PascalCase** describing what just happened.

```php
OrganizationCreated
OrganizationSuspended
UserInvited
UserRoleAssigned
PermissionRevoked
```
