# Build Roadmap

The Core platform is built in phases, where each phase delivers a stable, usable set of features before the next phase begins. Later phases depend on earlier ones, so the order is intentional.

**Current status:** Phases 1–4 and Phase 6 (SharedUI) are complete. Phase 5 (Settings) and Phases 7–8 are pending.

---

## Phase 1 — Organizations ✅ Complete

**Goal:** Establish the multi-tenancy foundation. Everything else is scoped to an organization.

**Deliverables:**

- `organizations` table: `id`, `name`, `slug`, `domain`, `status`, `settings_json`, soft deletes
- `organization_user` pivot: `user_id`, `organization_id`, `is_owner`, `status`, `joined_at`
- `OrganizationStatus` enum with `label()` and `color()` methods
- `OrganizationService`: `create()`, `update()`, `delete()`
- Livewire CRUD: List (paginated + search + status filter), Create, Edit

**Dependencies:** None

**Success criteria:**

- Organizations can be created, updated, and listed
- Slug is auto-generated from name and guaranteed unique
- Status filter works on list view

---

## Phase 2 — Users ✅ Complete

**Goal:** User management UI built on top of Laravel's base `users` table.

**Deliverables:**

- Livewire List: paginated user table with search, role display via spatie
- Livewire Create/Edit: name, email, password (on create), optional org assignment
- Routes: `core.users.index`, `core.users.create`, `core.users.edit`

**Dependencies:** Phase 1 (Organizations for org assignment)

**Success criteria:**

- Users can be created and edited via the UI
- New users can be assigned to an organization at creation time
- Spatie roles display in the user list

---

## Phase 3 — Roles & Permissions ✅ Complete

**Goal:** Fine-grained access control using spatie/laravel-permission.

**Deliverables:**

- spatie tables: `roles`, `permissions`, `model_has_roles`, `role_has_permissions`
- `RolesAndPermissionsSeeder`: 5 roles, 15 `core.*` permissions, idempotent
- `App\Models\User` extended with `HasRoles` trait
- Livewire Roles: list with permission/user counts, create with permission checkboxes
- Livewire Permissions: read-only list grouped by module prefix

**Dependencies:** spatie/laravel-permission ^6.0

**Success criteria:**

- `$user->can('core.organizations.view')` works after seeder runs
- `super-admin` role has all permissions
- Roles and permissions visible in the UI

---

## Phase 4 — Menu ✅ Complete

**Goal:** Config-driven, permission-aware sidebar navigation.

**Deliverables:**

- `config/menu.php`: 3 groups (Platform, Core, Access Control) with 5 items
- `MenuService::forUser()`: filters items by `$user->can($permission)` with graceful fallback
- Sidebar updated to read `MenuService` instead of hardcoded links

**Dependencies:** Phase 3 (spatie for permission checks)

**Success criteria:**

- Users with restricted roles see only permitted menu items
- Super-admin sees all items
- Groups with no visible items are hidden entirely

---

## Phase 5 — Settings (Pending)

**Goal:** Configurable platform and organization-level settings.

**Deliverables:**

- `settings` table: `key`, `value`, `type`, `scope`, `organization_id`
- `setting()` helper and `Settings` facade
- Default seeder: `app.name`, `app.timezone`, `app.locale`
- Livewire settings screens: General, Appearance
- Organization-scoped overrides

**Dependencies:** Phase 1 (Organizations)

**Success criteria:**

- `setting('app.name')` returns the correct value from the database
- Organization admins can override global settings
- New settings keys can be registered by modules during boot

---

## Phase 6 — SharedUI ✅ Complete

**Goal:** Reusable anonymous Blade component library.

**Deliverables:**

- `Blade::anonymousComponentPath()` registers components under `ui` prefix
- `<x-ui::page-header>`: title, description, backRoute, actions slot
- `<x-ui::card>`: bordered content card with optional title
- `<x-ui::empty-state>`: centered empty state with optional actions
- `<x-ui::alert>`: info/success/warning/error inline alert

**Dependencies:** Livewire Flux

**Success criteria:**

- Components usable from any module view via `<x-ui::*>`
- Dark mode works via `dark:` variants

---

## Phase 7 — Notifications, AuditLog, Files (Pending)

These three modules are independent of each other but all depend on the earlier foundation.

### Notifications

**Goal:** Multi-channel notification system with user preferences.

**Deliverables:**

- `notifications` table (Laravel default + `organization_id`)
- `notification_preferences` table: `user_id`, `type`, `channel`, `enabled`
- Email and in-app channel implementations
- Notification bell component in topbar
- User notification preferences screen

**Dependencies:** Phase 5 (Settings), Phase 2 (Users)

### AuditLog

**Goal:** Immutable record of significant platform events.

**Deliverables:**

- `audit_entries` table: `user_id`, `organization_id`, `model_type`, `model_id`, `action`, `old_values`, `new_values`, `ip_address`, `created_at`
- `Auditable` trait for opt-in model auditing
- Livewire audit log viewer with filters

**Dependencies:** Phase 2 (Users)

### Files

**Goal:** Unified file upload and management layer.

**Deliverables:**

- `files` table: `id`, `filename`, `path`, `disk`, `mime_type`, `size`, `organization_id`, `uploaded_by`, `fileable_type`, `fileable_id`
- `HasFiles` trait for polymorphic attachment
- Signed URL generation for private files
- Livewire file upload component
- S3 disk configuration support

**Dependencies:** Phase 1 (Organizations), Phase 2 (Users)

---

## Phase 8 — Dashboard & FeatureFlags (Pending)

### Dashboard

**Goal:** Configurable landing screen assembled from module widgets.

**Deliverables:**

- Widget registration system (modules declare widgets in their service provider)
- Default Core widgets: user count, recent audit log, organization info
- Livewire Dashboard page with responsive widget grid
- Role-based widget visibility

**Dependencies:** All Phase 1–7 modules

### FeatureFlags

**Goal:** Runtime feature toggling at organization or global level.

**Deliverables:**

- `feature_flags` table: `key`, `description`, `default_enabled`
- `organization_feature_flags` pivot: `organization_id`, `feature_flag_id`, `enabled`
- `feature()` helper: `feature('beta_ui')` returns bool
- Admin UI for managing flags globally
- Organization override UI

**Dependencies:** Phase 1 (Organizations)
