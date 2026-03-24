# Core Architecture

## Philosophy

This platform is designed to be a reusable foundation. Multiple product families (**ScheduleHub**, **Stocky**, **Workforce**, **DeliveryOS**, **EduCore**) will be built on top of it without duplicating authentication, authorization, organizations, notifications, or UI components.

To achieve this, the project draws a hard line between:

- **Infrastructure** — the minimal Laravel app layer (`app/`)
- **Platform Core** — shared business modules (`Modules/Core/*`)
- **App Modules** — product-specific modules (`Modules/[AppFamily]/*`)

## Why Core Lives in `Modules/Core/*`

### Module isolation

Each module in `Modules/Core/` is a self-contained unit with its own migrations, routes, Livewire components, service providers, and tests. This isolation means:

- A module can be enabled or disabled without touching unrelated code.
- App teams can depend on specific Core modules without pulling in the whole framework.
- Versioning and upgrades happen at the module level.

### Discoverability

Using `nwidart/laravel-modules`, every module under `Modules/` is automatically discovered, loaded, and registered. The `module.json` manifest declares providers, aliases, and priorities. There is no manual bootstrapping per module.

### Shared across apps

When a new app family is started (e.g., `Modules/ScheduleHub/`), it lists Core modules as dependencies. It does not re-implement users, roles, or organizations — it reuses them.

## Why Root `app/` Is Reserved for Infrastructure

The root `app/` directory contains only what Laravel requires to boot:

```
app/
├── Actions/          # Global form actions (e.g., Fortify auth actions)
├── Concerns/         # Shared traits used across modules
├── Http/
│   └── Middleware/   # Global middleware
├── Livewire/         # Global Livewire components (e.g., auth pages)
├── Models/           # Base model extensions (not domain models)
└── Providers/        # AppServiceProvider, RouteServiceProvider, etc.
```

Domain models, business logic, and feature modules do **not** live here. Placing business logic in `app/` would defeat the modular architecture and make it impossible to cleanly reuse Core across app families.

## Module Dependency Order

Modules are loaded by `nwidart/laravel-modules` according to their `priority` field in `module.json`. Lower numbers load first.

Recommended priority order for Core modules:

| Priority | Module | Reason |
|----------|--------|---------|
| 0 | Core | Base module, registers shared services |
| 10 | Organizations | Required by Users (org context) |
| 20 | Users | Required by Roles, Permissions |
| 30 | Roles | Required by Permissions |
| 40 | Permissions | Required by Menu, Settings, etc. |
| 50 | Menu | Depends on Permissions |
| 50 | Settings | Depends on Organizations |
| 60 | Notifications | Depends on Users |
| 60 | AuditLog | Depends on Users |
| 60 | Files | Depends on Organizations, Users |
| 70 | SharedUI | Pure UI, no domain deps |
| 80 | Dashboard | Depends on all data modules |
| 80 | FeatureFlags | Depends on Organizations |

> Set `priority` in each module's `module.json` to enforce this order.

## Core vs App Modules

```
Modules/
├── Core/               <-- Fixed. Stable. Never app-specific.
│   ├── Organizations/
│   ├── Users/
│   ├── Roles/
│   └── ...
│
├── ScheduleHub/        <-- App-specific. Depends on Core modules.
│   ├── Schedules/
│   ├── Bookings/
│   └── ...
│
└── Stocky/             <-- App-specific. Different product, same Core.
    ├── Inventory/
    ├── Suppliers/
    └── ...
```

### Rules for Core modules

- A Core module **never** imports from an App module.
- A Core module **may** import from another Core module (following the dependency order above).
- Breaking changes to a Core module require coordination across all app families.

### Rules for App modules

- App modules **may** import from Core modules.
- App modules **must not** modify Core module tables directly (use events and hooks instead).
- App modules can extend Core entities via separate pivot/extension tables.

## Fixed Shared Core Architecture

The 12 Core modules form a complete operational backbone:

```
Organizations ──► Users ──► Roles ──► Permissions
                                          │
                    ┌─────────────────────┤
                    ▼                     ▼
                  Menu              Settings
                    │
          ┌─────────┼──────────┐
          ▼         ▼          ▼
    Notifications AuditLog   Files
                    │
              SharedUI ──► Dashboard ──► FeatureFlags
```

Each layer depends on the one above it. The dependency graph is acyclic by design.
