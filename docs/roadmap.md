# Build Roadmap

The Core platform is built in phases. Each phase delivers a stable, usable set of features before the next begins.

**Current status:** Phases 1–8 are complete. All 13 modules are live. Phase 9 defines next-steps.

---

## Phase 1 — Organizations ✅ Complete

**Goal:** Multi-tenancy foundation.

- `organizations` table with status enum, soft deletes
- `organization_user` pivot: `is_owner`, `status`, `joined_at`
- `OrganizationService`, `OrganizationStatus` enum
- Livewire CRUD, paginated list with status filter

---

## Phase 2 — Users ✅ Complete

**Goal:** User management UI on Laravel's `users` table.

- Livewire List + Create/Edit
- Organization assignment on create
- Role assignment via spatie

---

## Phase 3 — Roles & Permissions ✅ Complete

**Goal:** Fine-grained access control.

- spatie/laravel-permission ^6.0
- `RolesAndPermissionsSeeder`: 5 roles, idempotent
- Livewire Roles (create + assign permissions) and Permissions (read-only list)

---

## Phase 4 — Menu ✅ Complete

**Goal:** Config-driven, permission-aware sidebar.

- `config/menu.php`: 4 groups — Platform, Core, Access Control, System
- `MenuService::forUser()` filters by `$user->can()`
- Groups with no visible items are hidden entirely

---

## Phase 5 — Settings ✅ Complete

**Goal:** Global and org-scoped key-value configuration store.

- `settings` table with `type`, `group`, `is_public`
- `SettingService`: get, set, has, forget — org-specific → global fallback
- Livewire list + edit UI
- Routes: `core.settings.index`, `core.settings.edit`

---

## Phase 6 — SharedUI ✅ Complete

**Goal:** Reusable anonymous Blade components.

- `<x-ui::page-header>`, `<x-ui::card>`, `<x-ui::empty-state>`, `<x-ui::alert>`
- Registered under `ui` prefix via `Blade::anonymousComponentPath()`

---

## Phase 7 — AuditLog, Notifications, Files ✅ Complete

### AuditLog

- `audit_logs` table (custom, no third-party package)
- `AuditLogger` service with `log()`, `created()`, `updated()`, `deleted()`
- Livewire list with search + event filter
- Route: `core.auditlog.index`

### Notifications

- Backed by Laravel's built-in `notifications` table
- `ListNotifications` Livewire: mark-read per item, mark-all-read, unread-only filter
- Route: `core.notifications.index`

### Files

- `files` table with polymorphic `attachable` support
- `FileService`: store, attach, delete
- Livewire list + upload form (max 10 MB, disk/visibility selector)
- Routes: `core.files.index`, `core.files.upload`
- Note: run `php artisan storage:link` for public disk access

---

## Phase 8 — Dashboard & FeatureFlags ✅ Complete

### Dashboard

- `DashboardIndex` Livewire: 4 summary cards (Orgs, Users, Notifications, Feature Flags)
- All counts use `class_exists()` guards — safe if any module is absent
- Permission-gated quick links to all core modules
- 7-day audit activity count
- Route: `core.dashboard.index`
- Permission: `core.dashboard.view`

### FeatureFlags

- `feature_flags` table with org-specific override support
- `FeatureFlagService` singleton: `isEnabled()`, `enable()`, `disable()`, `set()`, `has()`, `forget()`
- Resolution: org-specific → global → `false`
- Livewire list with inline toggle, create form
- Routes: `core.featureflags.index`, `core.featureflags.create`
- Permissions: `core.featureflags.view/create/update`

---

## Phase 9 — Next Steps (Planned)

| Area | Description |
| --- | --- |
| Tests | Add Pest tests for Users, Roles, Permissions, Menu, Settings, Notifications, Files, Dashboard, FeatureFlags |
| Dashboard widgets | Make the widget system pluggable — modules register widgets via service providers |
| FeatureFlags | Blade helper `@featureEnabled('key')` directive |
| FeatureFlags | Org-specific flag management UI |
| AuditLog | Hook Organizations, Users, Settings into `AuditLogger` automatically |
| Notifications | Email channel support and notification preferences screen |
| Files | Signed URL generation for private disk files, S3 support |
| Settings | `setting()` global helper for easy access |
| Roles | Edit route for existing roles |
| Gate policies | Move permission checks from raw `can()` to formal Laravel policies |
