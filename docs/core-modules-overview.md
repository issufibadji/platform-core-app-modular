# Core Modules Overview

The 12 Core modules form the shared operational backbone of the platform. Every app family built on this platform inherits these modules without reimplementing them.

---

## Organizations

**Purpose:** Manages the top-level tenant or account concept. Every user, setting, and resource belongs to an organization.

**Responsibilities:**
- Create, update, suspend, and archive organizations
- Manage organization slugs and optional custom domains
- Resolve the active organization from the request context (subdomain or header)
- Organization-level settings and metadata

**Main entities:** `Organization`, `OrganizationInvitation`

**Dependencies:** None (foundational module)

**Future use:** Every app family has organizations at its root. ScheduleHub has scheduling organizations; Stocky has inventory organizations; Workforce has employer organizations.

---

## Users

**Purpose:** Manages user accounts and their membership within organizations.

**Responsibilities:**
- User registration, profile management, and deactivation
- Linking users to one or more organizations
- User invitation and onboarding flows
- Two-factor authentication state
- Profile avatar via Files module

**Main entities:** `User`, `OrganizationUser` (pivot with role context)

**Dependencies:** Organizations

**Future use:** All apps need user management. The Users module provides a complete, extensible user entity that app modules can attach domain-specific data to.

---

## Roles

**Purpose:** Defines named roles that group permissions together.

**Responsibilities:**
- Create and manage roles scoped to an organization
- Assign roles to users within an organization
- List and filter roles
- Default roles seeding (e.g., Admin, Member, Viewer)

**Main entities:** `Role`, `UserRole` (pivot)

**Dependencies:** Organizations, Users

**Future use:** Apps define their own role sets on top of the Core roles infrastructure. ScheduleHub may add a "Scheduler" role; Workforce may add "HR Manager".

---

## Permissions

**Purpose:** Defines fine-grained permissions and maps them to roles.

**Responsibilities:**
- Seed and register all platform permissions
- Assign permissions to roles
- Check user permissions via Gate integration
- Sync permissions when roles change

**Main entities:** `Permission`, `RolePermission` (pivot)

**Dependencies:** Roles

**Future use:** Permission strings are namespaced by scope (`core.*`, `schedulehub.*`). App modules register their own permissions during boot, which are then assignable to roles.

---

## Menu

**Purpose:** Manages the navigation menu dynamically based on user permissions.

**Responsibilities:**
- Define menu items (label, icon, route, permission gate)
- Order and group menu items
- Resolve the active menu for the current user and organization
- Support nested (hierarchical) menu structures

**Main entities:** `MenuItem`, `MenuGroup`

**Dependencies:** Permissions

**Future use:** App modules register their own menu items during boot. The Menu module assembles the final navigation tree at runtime, showing only what the user is permitted to see.

---

## Settings

**Purpose:** Stores and retrieves configuration values at the organization or global level.

**Responsibilities:**
- Key-value settings storage with typed values
- Organization-scoped overrides of global defaults
- Settings UI for administrators
- Accessor helper (e.g., `setting('app.name')`)

**Main entities:** `Setting` (key, value, scope, organization_id)

**Dependencies:** Organizations

**Future use:** Each app module registers its own settings keys. The Settings module provides a unified UI and API for all of them. Users configure `notifications.email.enabled` in the same interface as `schedulehub.booking.buffer_minutes`.

---

## Notifications

**Purpose:** Sends and tracks notifications to users via multiple channels.

**Responsibilities:**
- Queue and dispatch email, in-app, and push notifications
- User notification preferences (opt-in/out per channel)
- Notification history and read/unread state
- Template management for email content

**Main entities:** `Notification`, `NotificationPreference`

**Dependencies:** Users, Settings (for channel configuration)

**Future use:** App modules trigger notifications via the Notifications module. A booking confirmation in ScheduleHub uses the same notification infrastructure as a stock alert in Stocky.

---

## AuditLog

**Purpose:** Records significant user actions for compliance and debugging.

**Responsibilities:**
- Log create/update/delete events on key models
- Record actor (user), target (model), action type, and before/after values
- Expose a filterable audit log UI for administrators
- Configurable retention policy

**Main entities:** `AuditEntry` (user_id, model_type, model_id, action, payload, ip_address, created_at)

**Dependencies:** Users

**Future use:** App modules opt their models into auditing. The audit log viewer in the Core admin panel shows activity across all modules in a unified timeline.

---

## Files

**Purpose:** Manages file uploads and serves them through a consistent API.

**Responsibilities:**
- Upload files to configured disk (local or S3)
- Track file metadata (name, mime, size, uploader, organization)
- Generate signed temporary URLs for private files
- Associate files with any model (polymorphic)
- Enforce file type and size restrictions

**Main entities:** `File` (path, disk, mime_type, size, organization_id, uploaded_by, fileable_type, fileable_id)

**Dependencies:** Organizations, Users

**Future use:** Any module that needs file handling (profile avatars, document attachments, logos) uses the Files module rather than implementing its own upload logic.

---

## SharedUI

**Purpose:** Provides a library of reusable Blade/Livewire/Flux components and layouts.

**Responsibilities:**
- Base page layouts (sidebar, topbar, content area)
- Reusable UI components: data tables, modals, confirmation dialogs, empty states
- Flash message and toast notification components
- Form components: text inputs, selects, toggles, date pickers
- Consistent styling via Tailwind/Flux tokens

**Main entities:** Blade components only (no database tables)

**Dependencies:** None (pure UI layer)

**Future use:** Every module's Livewire views extend SharedUI layouts and use SharedUI components. App families inherit the full UI system without rebuilding it.

---

## Dashboard

**Purpose:** Provides the main authenticated landing screen with summary widgets.

**Responsibilities:**
- Render a configurable grid of dashboard widgets
- Each widget is a self-contained Livewire component
- Widgets pull data from other Core modules (users count, recent audit log, etc.)
- Role-based widget visibility

**Main entities:** `DashboardWidget` configuration (no separate table; widgets are code-defined)

**Dependencies:** Users, Organizations, AuditLog, FeatureFlags

**Future use:** App modules register their own widgets (e.g., upcoming bookings for ScheduleHub, low stock alerts for Stocky). The Dashboard assembles them into a unified view.

---

## FeatureFlags

**Purpose:** Controls feature availability at the organization or global level without deployments.

**Responsibilities:**
- Define boolean feature flags with default values
- Override flags per organization
- Expose a management UI for administrators
- Integrate with Livewire components to show/hide features at runtime

**Main entities:** `FeatureFlag` (key, default_enabled, description), `OrganizationFeatureFlag` (pivot with override value)

**Dependencies:** Organizations

**Future use:** App modules ship features behind flags. During rollout, flags can be enabled for a specific organization before being globally released. Flags are the deployment mechanism for incremental feature launches.
