# FeatureFlags Module

## Purpose

Enable or disable named features globally or per organization. Provides a service-based API for conditional feature activation throughout the platform.

## Location

`Modules/FeatureFlags/`

## Module Details

| Property | Value |
|---|---|
| Name | `FeatureFlags` |
| Alias | `featureflags` |
| Priority | `19` |
| Provider | `Modules\FeatureFlags\Providers\FeatureFlagsServiceProvider` |

## Files Created

```
Modules/FeatureFlags/
├── app/
│   ├── Http/
│   │   ├── Controllers/FeatureFlagsController.php
│   │   └── Livewire/
│   │       ├── CreateFeatureFlag.php
│   │       └── ListFeatureFlags.php
│   ├── Models/FeatureFlag.php
│   ├── Providers/
│   │   ├── EventServiceProvider.php
│   │   ├── FeatureFlagsServiceProvider.php
│   │   └── RouteServiceProvider.php
│   └── Services/FeatureFlagService.php
├── composer.json
├── database/
│   └── migrations/
│       └── 2026_03_25_100000_create_feature_flags_table.php
├── module.json
├── resources/
│   └── views/
│       ├── create.blade.php
│       ├── index.blade.php
│       └── livewire/
│           ├── create-feature-flag.blade.php
│           └── list-feature-flags.blade.php
└── routes/
    └── web.php
```

## Migration

**Table:** `feature_flags`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint | PK |
| `key` | string | Flag identifier |
| `description` | text | nullable |
| `organization_id` | bigint | nullable, for org-specific flags |
| `module` | string(64) | nullable, categorizes by module |
| `is_enabled` | boolean | default `false` |
| `created_at` / `updated_at` | timestamps | |

Unique constraint on `(key, organization_id)`.

Run: `php artisan migrate`

## Model — `FeatureFlag`

**Fillable:** `key`, `description`, `organization_id`, `module`, `is_enabled`

**Scopes:**
- `->global()` — flags with `organization_id = null`
- `->enabled()` — flags with `is_enabled = true`
- `->forOrganization(int $id)` — org-specific flags

**Relationship:** `organization()` → `BelongsTo Organization`

## Service — `FeatureFlagService`

Registered as a singleton in the service container.

```php
use Modules\FeatureFlags\Services\FeatureFlagService;

$service = app(FeatureFlagService::class);
```

### Methods

| Method | Signature | Description |
|---|---|---|
| `isEnabled` | `(string $key, ?int $organizationId = null): bool` | Org-specific override → global fallback |
| `enable` | `(string $key, ?int $organizationId = null): FeatureFlag` | Enable a flag |
| `disable` | `(string $key, ?int $organizationId = null): FeatureFlag` | Disable a flag |
| `set` | `(string $key, bool $enabled, ?int $orgId, ?string $desc, ?string $module): FeatureFlag` | Create or update |
| `has` | `(string $key, ?int $organizationId = null): bool` | Check existence |
| `forget` | `(string $key, ?int $organizationId = null): void` | Delete a flag |

### Resolution logic

1. If `$organizationId` is given, look for a flag with that `organization_id` — return its `is_enabled` value.
2. If not found (or `$organizationId` is null), look for a global flag (`organization_id = null`).
3. Return `false` if no matching record exists.

## Routes

| Name | URL | Method | Permission |
|---|---|---|---|
| `core.featureflags.index` | `/core/feature-flags` | GET | `core.featureflags.view` |
| `core.featureflags.create` | `/core/feature-flags/create` | GET | `core.featureflags.create` |

Note: Toggle (enable/disable) is handled via Livewire `wire:click="toggle(id)"` in `ListFeatureFlags`, no separate route needed.

## Livewire Components

| Component Tag | Class | Description |
|---|---|---|
| `featureflags.list-feature-flags` | `ListFeatureFlags` | Paginated list with search, status filter, toggle action |
| `featureflags.create-feature-flag` | `CreateFeatureFlag` | Form to create a new global flag |

## Permissions

| Permission | Roles with access |
|---|---|
| `core.featureflags.view` | super-admin, owner |
| `core.featureflags.create` | super-admin, owner |
| `core.featureflags.update` | super-admin, owner |

## Menu Integration

FeatureFlags appears under the **System** group in `Modules/Menu/config/menu.php`.

## Usage Examples

```php
// Check if a feature is enabled
$service = app(\Modules\FeatureFlags\Services\FeatureFlagService::class);

if ($service->isEnabled('new-dashboard-ui')) {
    // show new UI
}

// Check for a specific organization
if ($service->isEnabled('beta-reporting', organizationId: $org->id)) {
    // show beta report
}

// Enable a flag programmatically
$service->enable('maintenance-mode');

// Create via seeder
$service->set('dark-mode', true, null, 'Enables dark mode globally', 'Dashboard');
```

## Limitations / Next Steps

- No UI for editing existing flags (only toggle enabled/disabled via list)
- No org-specific flag UI (all created flags are currently global)
- No audit logging of flag changes
- No Blade directive helper (e.g. `@featureEnabled('key')`)
