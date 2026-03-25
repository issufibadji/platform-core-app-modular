# Build Roadmap

The Core platform is built in phases, where each phase delivers a stable, usable set of features before the next phase begins. Later phases depend on earlier ones, so the order is intentional.

**Current status:** Phases 1–4 and Phase 6 (SharedUI) are complete. Phase 5 (Settings) and Phases 7–8 are pending.

---

## Phase 1 — Organizations ✅ Complete

**Goal:** Establish the multi-tenancy foundation. Everything else is scoped to an organization.

**Deliverables:**
- `organizations` table with fields: `id`, `name`, `slug`, `domain`, `status`, `settings_json`, `created_at`, `updated_at`
- `Organization` model with status enum, slug uniqueness, and soft deletes
- Livewire screens: List, Create, Edit, View, Suspend/Archive
- `OrganizationPolicy` with `core.organizations.*` gates
- Organization context resolver (middleware that sets the active organization from subdomain or header)
- Seeder with sample organizations

**Dependencies:** None

**Success criteria:**
- Organizations can be created, updated, suspended, and archived
- All subsequent modules can resolve `organization_id` from the request context
- Policy gates enforce access correctly

---

## Phase 2 — Users

**Goal:** User management with full organization membership support.

**Deliverables:**
- `organization_user` pivot table: `user_id`, `organization_id`, `role_context`, `is_owner`, `joined_at`
- User invitation flow: invite by email → accept → join organization
- Profile management: name, avatar (via Files module), timezone, locale
- Livewire screens: List Users, Invite User, Edit Profile, Deactivate User
- `UserPolicy` with `core.users.*` gates

**Dependencies:** Phase 1 (Organizations)

**Success criteria:**
- Users can be invited to an organization and accept the invitation
- Users belong to one or more organizations
- Profile updates are saved and visible immediately

---

## Phase 3 — Roles & Permissions

**Goal:** Fine-grained access control across the platform.

**Deliverables:**
- `roles` table: `id`, `name`, `slug`, `organization_id`, `is_system`
- `permissions` table: `id`, `name` (string key like `core.organizations.view`), `description`
- `role_permission` pivot table
- `user_role` pivot: `user_id`, `role_id`, `organization_id`
- Default roles seeder: Admin, Member, Viewer
- Full permissions seeder for all `core.*` permissions
- Livewire screens: Roles list, Edit Role (assign permissions), Assign Role to User
- Gate integration: `$user->can('core.organizations.create')`

**Dependencies:** Phase 2 (Users)

**Success criteria:**
- The Admin role can access everything
- A Member role can be restricted to read-only
- Permissions can be assigned to roles via UI
- Gate checks work on all existing Livewire screens

---

## Phase 4 — Menu

**Goal:** Dynamic, permission-aware navigation.

**Deliverables:**
- `menu_items` table: `id`, `label`, `icon`, `route`, `permission`, `parent_id`, `order`, `module`
- Default menu seeder with Core navigation items
- Menu resolver: builds the visible menu tree for the current user based on their permissions
- Livewire `AppSidebar` component that renders the resolved menu
- Admin screen for reordering menu items

**Dependencies:** Phase 3 (Permissions)

**Success criteria:**
- Admin users see all menu items
- Restricted users see only menu items their permissions allow
- App modules can register menu items during boot

---

## Phase 5 — Settings

**Goal:** Configurable platform and organization-level settings.

**Deliverables:**
- `settings` table: `id`, `key`, `value`, `type` (string/bool/int/json), `scope` (global/organization), `organization_id`
- `setting()` helper function and `Settings` facade
- Default settings seeder: `app.name`, `app.timezone`, `app.locale`
- Livewire settings management screens: General, Appearance
- Organization-scoped overrides (organization can override global defaults)

**Dependencies:** Phase 1 (Organizations)

**Success criteria:**
- `setting('app.name')` returns the correct value from the database
- Organization admins can override global settings
- New settings keys can be registered by modules during boot

---

## Phase 6 — SharedUI

**Goal:** A consistent, reusable UI component library for all module screens.

**Deliverables:**
- Base authenticated layout: sidebar (Menu), topbar, content area, breadcrumbs
- Data table component: sortable columns, pagination, search input, empty state
- Modal and confirmation dialog components
- Form field components: text input, select, toggle, date picker, file upload
- Flash message and toast notification components
- Empty state and loading skeleton components

**Dependencies:** Phase 4 (Menu for sidebar integration)

**Success criteria:**
- All existing Livewire screens are refactored to use SharedUI components
- New Livewire screens only need to compose from SharedUI primitives
- Dark mode and responsive layout work across all screens

---

## Phase 7 — Notifications, AuditLog, Files

These three modules are independent of each other but all depend on the earlier foundation. They can be developed in parallel.

### Notifications

**Goal:** Multi-channel notification system with user preferences.

**Deliverables:**
- `notifications` table (Laravel default + `organization_id`)
- `notification_preferences` table: `user_id`, `type`, `channel`, `enabled`
- Email and in-app channel implementations
- Notification bell component in topbar
- User notification preferences screen
- Settings integration: `notifications.email.enabled`

**Dependencies:** Phase 5 (Settings), Phase 2 (Users)

### AuditLog

**Goal:** Immutable record of significant platform events.

**Deliverables:**
- `audit_entries` table: `user_id`, `organization_id`, `model_type`, `model_id`, `action`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`
- `Auditable` trait for opt-in model auditing
- Livewire audit log viewer with filters (user, model, action, date range)

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

**Phase 7 success criteria:**
- Notifications are delivered via email and displayed in the topbar bell
- All create/update/delete actions on Core models appear in the audit log
- Files can be uploaded, listed, and served via signed URLs

---

## Phase 8 — Dashboard & FeatureFlags

### Dashboard

**Goal:** Configurable landing screen assembled from module widgets.

**Deliverables:**
- Widget registration system (modules declare widgets in their service provider)
- Default Core widgets: user count, recent audit log, organization info
- Livewire `Dashboard` page with responsive widget grid
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

**Phase 8 success criteria:**
- Admin can toggle features per organization without a deployment
- Dashboard renders correctly with all available Core widgets
- App modules can add their own feature flags and dashboard widgets
