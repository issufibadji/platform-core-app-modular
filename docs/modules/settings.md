# Settings Module

**Path:** `Modules/Settings/`
**Status:** Live
**Priority:** 15

---

## Purpose

Provides a global and organization-scoped key-value configuration store. Settings can be typed (string, boolean, integer, float, json) and optionally marked as public for unauthenticated access.

---

## Database

### `settings` table

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| organization_id | bigint nullable | FK → organizations (null = global) |
| module | string nullable | e.g. `files`, `mail` |
| key | string | Setting key (unique per org_id) |
| value | text nullable | Raw stored value |
| type | string | `string`, `boolean`, `integer`, `float`, `json` |
| group | string nullable | Logical grouping for display |
| is_public | boolean | Default false |
| created_at / updated_at | timestamps | |

**Unique constraint:** `(organization_id, key)`

---

## Model

`Modules\Settings\Models\Setting`

- `typedValue()` — casts `value` to the correct PHP type based on `type`
- Scopes: `global()`, `forOrganization($id)`, `forModule($module)`, `forGroup($group)`, `public()`

---

## Service

`Modules\Settings\Services\SettingService`

```php
// Resolve with fallback: org-specific → global → default
$value = app(SettingService::class)->get('app.timezone', 'UTC', $orgId);

// Persist
app(SettingService::class)->set(
    key: 'app.timezone',
    value: 'America/New_York',
    type: 'string',
    organizationId: null,
    module: 'core',
    group: 'locale',
);

// Check existence
app(SettingService::class)->has('app.timezone');

// Delete
app(SettingService::class)->forget('app.timezone');

// All global settings
app(SettingService::class)->all(module: 'core', group: 'locale');
```

---

## Routes

| Name | Method | URL | Description |
| --- | --- | --- | --- |
| `core.settings.index` | GET | `/core/settings` | List all global settings |
| `core.settings.edit` | GET | `/core/settings/{setting}` | Edit a setting |

---

## Livewire Components

| Component | Tag | Description |
| --- | --- | --- |
| `ListSettings` | `settings.list-settings` | Paginated list with search and group filter |
| `EditSetting` | `settings.edit-setting` | Edit value, type, group, is_public |

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.settings.view` | View the settings list and edit form |
| `core.settings.update` | Save changes to settings |

---

## Limitations / Next Steps

- UI does not support creating settings from scratch — settings are expected to be seeded or created programmatically
- No per-organization UI yet (only global settings shown)
- No audit integration hook yet
