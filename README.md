# Platform Core — Documentation Index

## Project Overview

**Platform Core** is a modular Laravel 13 application that serves as a shared foundation for multiple product families. Built with Livewire, Tailwind CSS (via Flux), and [nwidart/laravel-modules](https://github.com/nWidart/laravel-modules), it provides a set of reusable Core modules that any future application can depend on without duplication.

The platform is designed so that product teams building apps like **ScheduleHub**, **Stocky**, **Workforce**, **DeliveryOS**, or **EduCore** can inherit a fully working authentication, authorization, organization, notification, and settings layer from day one.

## Architecture Summary

```
platform-core-app-modular/
├── app/                        # Laravel infrastructure only (Providers, base Models, etc.)
├── Modules/
│   ├── Core/                   # Shared platform foundation (fixed, stable)
│   │   ├── Users/
│   │   ├── Roles/
│   │   ├── Permissions/
│   │   ├── Menu/
│   │   ├── Settings/
│   │   ├── Notifications/
│   │   ├── AuditLog/
│   │   ├── Organizations/
│   │   ├── Files/
│   │   ├── SharedUI/
│   │   ├── Dashboard/
│   │   └── FeatureFlags/
│   └── [AppFamily]/            # Future app-specific modules (e.g., ScheduleHub, Stocky)
├── database/                   # Laravel base migrations (users, cache, jobs)
└── resources/                  # Global Blade layouts and assets
```

**Tech stack:**
- Laravel 13 (PHP 8.3+)
- Livewire 4.1 + Livewire Flux 2.12
- Tailwind CSS (via Flux)
- nwidart/laravel-modules 13.0
- Laravel Fortify (authentication)
- SQLite (development) / MySQL (production)
- Pest (testing)

## Documentation Index

| File | Description |
|------|-------------|
| [installation.md](installation.md) | Full setup guide for this project |
| [core-architecture.md](core-architecture.md) | Why modules live in `Modules/Core/*`, dependency order |
| [module-structure.md](module-structure.md) | Standard folder structure inside every module |
| [naming-conventions.md](naming-conventions.md) | Namespaces, permissions, settings keys, routes |
| [commands.md](commands.md) | Artisan and Composer commands with usage context |
| [core-modules-overview.md](core-modules-overview.md) | Purpose and responsibilities of each Core module |
| [roadmap.md](roadmap.md) | Phased build plan for Core modules |
| [modules/organizations.md](modules/organizations.md) | Organizations module: tables, models, routes, permissions |

## Quick Navigation

- **Setting up the project for the first time?** → [installation.md](installation.md)
- **Understanding why the code is structured this way?** → [core-architecture.md](core-architecture.md)
- **Creating a new module?** → [module-structure.md](module-structure.md) + [commands.md](commands.md)
- **Naming a permission, setting, or route?** → [naming-conventions.md](naming-conventions.md)
- **Knowing what each Core module does?** → [core-modules-overview.md](core-modules-overview.md)
- **What to build next?** → [roadmap.md](roadmap.md)
