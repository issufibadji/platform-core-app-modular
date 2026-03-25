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

- `config/menu.php` — defines 4 groups: Platform (Dashboard), Core (Organizations, Users), Access Control (Roles, Permissions), System (Settings, Feature Flags, Audit Log, Notifications, Files)
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

## Settings ✅ LIVE

**Path:** `Modules/Settings/`
**Priority:** 15
**Purpose:** Global and organization-scoped key-value configuration store.

**Implemented:**

- `settings` table: `id`, `organization_id` (nullable), `module`, `key`, `value` (text), `type`, `group`, `is_public`, timestamps
- Unique constraint: `(organization_id, key)`
- `Setting` model with `typedValue()` method (casts to boolean/integer/float/json/string)
- `SettingService`: `get(key, default, orgId)`, `set(...)`, `has(key, orgId)`, `forget(key, orgId)`, `all(module, group)`
- Resolution priority: org-specific → global → default
- Livewire: `ListSettings` (paginated, search, group filter), `EditSetting` (value, type, group, is_public)
- Routes: `core.settings.index`, `core.settings.edit`
- Permissions: `core.settings.view`, `core.settings.update`

**Dependencies:** Organizations (FK on `organization_id`)

---

## AuditLog ✅ LIVE

**Path:** `Modules/AuditLog/`
**Priority:** 16
**Purpose:** Platform-wide audit trail for administrative and sensitive actions.

**Implemented:**

- `audit_logs` table: `id`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values` (json), `new_values` (json), `url`, `ip_address`, `user_agent`, `tags` (json), timestamps
- Polymorphic `auditable` relationship
- `AuditEntry` model with `user()` and `auditable()` relationships
- `AuditLogger` service: `log()`, `created()`, `updated()`, `deleted()` helpers
- Livewire: `ListAuditLogs` (paginated, search, event filter)
- Routes: `core.auditlog.index`
- Permissions: `core.auditlog.view`

**Usage:** Call `app(AuditLogger::class)->created($model)` after any auditable action.

**Dependencies:** None (custom implementation, no third-party auditing package)

---

## Notifications ✅ LIVE

**Path:** `Modules/Notifications/`
**Priority:** 17
**Purpose:** Internal notification centre backed by Laravel's database notification system.

**Implemented:**

- Uses Laravel's built-in `notifications` table (requires `php artisan notifications:table && php artisan migrate`)
- `ListNotifications` Livewire component: paginated list, unread count badge, mark-read per item, mark-all-read
- Filter: unread-only toggle
- Routes: `core.notifications.index`
- Permissions: `core.notifications.view`

**Notification data convention:** `data['title']` and `data['body']` are rendered in the UI.

**Dependencies:** Laravel's `Notifiable` trait on `App\Models\User` (already present in the starter kit)

---

## Files ✅ LIVE

**Path:** `Modules/Files/`
**Priority:** 18
**Purpose:** Platform-level file upload and attachment foundation.

**Implemented:**

- `files` table: `id`, `organization_id`, `disk`, `path`, `original_name`, `mime_type`, `extension`, `size`, `visibility`, `uploaded_by`, `attachable_type`, `attachable_id`, timestamps
- `File` model with `url()`, `humanSize()`, `uploader()`, `attachable()` helpers
- `FileService`: `store(UploadedFile, disk, directory, orgId, visibility)`, `attachTo(file, model)`, `delete(file)`
- Livewire `ListFiles`: paginated, search, delete with confirmation modal
- Livewire `UploadFile`: Livewire `WithFileUploads`, disk / directory / visibility selection (max 10 MB)
- Routes: `core.files.index`, `core.files.upload`
- Permissions: `core.files.view`, `core.files.upload`

> **Note:** For `public` disk web access run `php artisan storage:link` once after deployment.

**Dependencies:** Organizations (FK), Users (FK on `uploaded_by`)

---

## Dashboard ✅ LIVE

**Path:** `Modules/Dashboard/`
**Priority:** 2
**Purpose:** Platform home screen with summary widgets and quick navigation.

**Implemented:**

- `DashboardIndex` Livewire component with 4 summary cards: Organizations, Users, Unread Notifications, Active Feature Flags
- All counts use `class_exists()` guards — safe even if a module is absent
- Quick links section rendered with `@can` gates — links hidden when permission is absent
- Recent Activity section showing 7-day audit event count
- Route: `core.dashboard.index`
- Permission: `core.dashboard.view`

**Dependencies:** Organizations, AuditLog, Notifications, FeatureFlags (all optional/graceful)

---

## FeatureFlags ✅ LIVE

**Path:** `Modules/FeatureFlags/`
**Priority:** 19
**Purpose:** Enable/disable named features globally or per organization.

**Implemented:**

- `feature_flags` table: `id`, `key`, `description`, `organization_id` (nullable), `module`, `is_enabled`, timestamps
- Unique constraint: `(key, organization_id)`
- `FeatureFlag` model with `global()`, `enabled()`, `forOrganization()` scopes
- `FeatureFlagService` singleton: `isEnabled()`, `enable()`, `disable()`, `set()`, `has()`, `forget()`
- Resolution: org-specific override → global fallback → `false`
- Livewire `ListFeatureFlags`: paginated, search, status filter, inline toggle
- Livewire `CreateFeatureFlag`: key (validated regex), module, description, initial state
- Routes: `core.featureflags.index`, `core.featureflags.create`
- Permissions: `core.featureflags.view`, `core.featureflags.create`, `core.featureflags.update`

**Dependencies:** Organizations (optional FK)

---

## Planned Modules

All planned modules from Phase 1 and Phase 2 are now implemented. See [roadmap.md](roadmap.md) for next phases.
