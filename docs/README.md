# Platform Core — Documentation Index

## Project Overview

**Platform Core** is a modular Laravel 13 application that serves as a shared foundation for multiple product families. Built with Livewire v3, Tailwind CSS (via Flux), and [nwidart/laravel-modules v13](https://github.com/nWidart/laravel-modules), it provides a set of reusable Core modules that any future application can depend on without duplication.

The platform is designed so that product teams building apps like **ScheduleHub**, **Stocky**, **Workforce**, **DeliveryOS**, or **EduCore** can inherit a fully working authentication, authorization, organization, navigation, and UI layer from day one.

## Architecture Summary

```
platform-core-app-modular/
├── app/                        # Laravel infrastructure (Providers, base Models)
│   └── Models/User.php         # HasRoles trait from spatie/laravel-permission
├── Modules/
│   ├── Core/                   # Empty shell module (bootstraps the module system)
│   ├── Organizations/          # Multi-tenancy foundation ✅ LIVE
│   ├── Users/                  # User CRUD UI ✅ LIVE
│   ├── Roles/                  # Role management via spatie ✅ LIVE
│   ├── Permissions/            # Permission listing via spatie ✅ LIVE
│   ├── Menu/                   # Config-driven, permission-filtered nav ✅ LIVE
│   └── SharedUI/               # Anonymous Blade component library ✅ LIVE
├── database/
│   ├── migrations/             # Base Laravel migrations + spatie permission tables
│   └── seeders/
│       └── RolesAndPermissionsSeeder.php  # Seeds 5 roles + 15 permissions
└── resources/
    └── views/layouts/app/sidebar.blade.php  # Uses MenuService::forUser()
```

**Tech stack:**
- Laravel 13 (PHP 8.3+)
- Livewire 4.1 (v3 API) + Livewire Flux 2.12
- Tailwind CSS v4 (via `@tailwindcss/vite`)
- Vite 6.x (requires Node 18+, tested on Node 22.7)
- nwidart/laravel-modules 13.0
- spatie/laravel-permission ^6.0
- SQLite (development) / MySQL (production)
- Pest (testing)

## Documentation Index

| File | Description |
|------|-------------|
| [installation.md](installation.md) | Full setup guide including spatie and module activation |
| [core-architecture.md](core-architecture.md) | Module resolution, Livewire patterns, service provider loading |
| [module-structure.md](module-structure.md) | Standard folder structure inside every module |
| [naming-conventions.md](naming-conventions.md) | Namespaces, permissions, routes, component names |
| [commands.md](commands.md) | Artisan and Composer commands with usage context |
| [core-modules-overview.md](core-modules-overview.md) | Purpose and status of each module |
| [roadmap.md](roadmap.md) | Phased build plan for remaining Core modules |
| [modules/organizations.md](modules/organizations.md) | Organizations: tables, models, routes, permissions |
| [modules/users.md](modules/users.md) | Users: CRUD UI, routes, permissions |
| [modules/roles.md](modules/roles.md) | Roles: spatie integration, routes, permissions |
| [modules/permissions.md](modules/permissions.md) | Permissions: listing, seeder, spatie integration |
| [modules/menu.md](modules/menu.md) | Menu: config-driven nav with permission filtering |
| [modules/sharedui.md](modules/sharedui.md) | SharedUI: anonymous Blade component library |

## Quick Navigation

- **Setting up the project for the first time?** → [installation.md](installation.md)
- **Understanding the module + Livewire pattern?** → [core-architecture.md](core-architecture.md)
- **Creating a new module?** → [module-structure.md](module-structure.md) + [commands.md](commands.md)
- **Naming a permission, route, or component?** → [naming-conventions.md](naming-conventions.md)
- **What each module does?** → [core-modules-overview.md](core-modules-overview.md)
- **What to build next?** → [roadmap.md](roadmap.md)
