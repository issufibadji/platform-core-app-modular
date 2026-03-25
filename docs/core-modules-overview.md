# Core Modules Overview

The following modules are currently implemented and live. The remaining modules are planned for future phases (see [roadmap.md](roadmap.md)).

---

## Organizations ✅ LIVE

**Path:** `Modules/Organizations/`
**Priority:** 0
**Purpose:** Multi-tenancy foundation. Manages the top-level account concept.

**Implemented:**

- `organizations` table: `id`, `name`, `slug`, `domain`, `status` (enum), `settings_json`, soft deletes
- `organization_user` pivot: `user_id`, `organization_id`, `is_owner`, `joined_at`
- Livewire CRUD: List (paginated + search + status filter), Create, Edit
- `OrganizationStatus` enum with `label()` and `color()` methods
- `OrganizationService`: `create()`, `update()`, `delete()`
- Routes: `core.organizations.index`, `core.organizations.create`, `core.organizations.edit`
- Permissions: `core.organizations.view/create/update/delete`

**Dependencies:** None (foundational module)

---

## Users ✅ LIVE

**Path:** `Modules/Users/`
**Priority:** 10
**Purpose:** UI for managing `App\Models\User` records.

**Implemented:**

- Livewire CRUD: List (paginated + search), Create/Edit form
- Role display via spatie `getRoleNames()`
- Optional organization assignment on create
- Routes: `core.users.index`, `core.users.create`, `core.users.edit`
- Permissions: `core.users.view/create/update/delete`

**Dependencies:** Organizations (for `organization_user` pivot)

---

## Roles ✅ LIVE

**Path:** `Modules/Roles/`
**Priority:** 20
**Purpose:** Manage spatie roles and their permission assignments.

**Implemented:**

- Livewire List: shows all roles with permission count and user count (`withCount`)
- Livewire Create/Edit: create role, assign permissions grouped by `module.resource` prefix
- Routes: `core.roles.index`, `core.roles.create`
- Permissions: `core.roles.view/create/update/delete`

**Dependencies:** spatie/laravel-permission ^6.0

---

## Permissions ✅ LIVE

**Path:** `Modules/Permissions/`
**Priority:** 21
**Purpose:** Read-only listing of all platform permissions, grouped by module.

**Implemented:**

- Livewire List: groups `Spatie\Permission\Models\Permission` by first segment of name (e.g., `core`)
- Routes: `core.permissions.index`
- Permissions: `core.permissions.view/create`

**Dependencies:** spatie/laravel-permission ^6.0

---

## Menu ✅ LIVE

**Path:** `Modules/Menu/`
**Priority:** 30
**Purpose:** Config-driven navigation filtered by user permissions.

**Implemented:**

- `config/menu.php` — defines 3 groups: Platform (Dashboard), Core (Organizations, Users), Access Control (Roles, Permissions)
- `MenuService::forUser()` — filters groups/items by `auth()->user()->can($permission)` with graceful fallback
- Registered as a singleton in `MenuServiceProvider`
- Sidebar reads `MenuService` via `app(\Modules\Menu\Services\MenuService::class)->forUser()`

**No database tables** — config-driven only.

**Dependencies:** spatie/laravel-permission (for permission checks)

---

## SharedUI ✅ LIVE

**Path:** `Modules/SharedUI/`
**Priority:** 40
**Purpose:** Anonymous Blade component library used by all module views.

**Implemented:**

- `Blade::anonymousComponentPath()` registers components under the `ui` prefix
- Usage: `<x-ui::page-header />`, `<x-ui::card />`, `<x-ui::empty-state />`, `<x-ui::alert />`
- Components: `page-header` (title, description, backRoute, actions slot), `card` (title, padding), `empty-state` (title, description, actions slot), `alert` (type: info/success/warning/error)

**No database tables.**

**Dependencies:** Livewire Flux (uses `flux:*` components internally)

---

## Planned Modules

The following modules are designed and documented but not yet implemented:

| Module | Priority | Description |
| --- | --- | --- |
| Settings | 50 | Key-value config at global or organization scope |
| Notifications | 60 | Multi-channel notification system with user preferences |
| AuditLog | 70 | Immutable record of significant platform events |
| Files | 80 | Unified file upload and management layer |
| Dashboard | 90 | Configurable landing screen with module widgets |
| FeatureFlags | 100 | Runtime feature toggling per organization |

See [roadmap.md](roadmap.md) for the phased build plan.
