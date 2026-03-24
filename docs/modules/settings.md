# Module: Settings

**Path:** `Modules/Core/Settings/` (planned)
**Alias:** `settings`
**Priority:** 50
**Status:** Planned

---

## Purpose

Stores and retrieves typed configuration values at the global level or scoped to a specific organization. Replaces hardcoded config values for anything that administrators need to change at runtime.

---

## Database Tables

### `settings`

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `key` | `varchar(255)` | Dot-notation key: `app.name`, `notifications.email.enabled` |
| `value` | `text\|null` | Serialized value |
| `type` | `varchar(50)` | `string`, `boolean`, `integer`, `json` |
| `scope` | `varchar(50)` | `global` or `organization` |
| `organization_id` | `bigint unsigned\|null` | Set when scope = `organization` |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |

Unique constraint: `(key, organization_id)` — one value per key per org (null org_id = global).

---

## Models

### `Setting`

```php
namespace Modules\Core\Settings\Models;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'scope', 'organization_id'];

    public function getTypedValue(): mixed
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'json'    => json_decode($this->value, true),
            default   => $this->value,
        };
    }
}
```

---

## Helper Function

```php
// Global helper
function setting(string $key, mixed $default = null): mixed
{
    $organizationId = auth()->user()?->currentOrganization()?->id;

    // Try organization-scoped first
    $setting = Setting::query()
        ->where('key', $key)
        ->where('organization_id', $organizationId)
        ->first();

    // Fall back to global
    $setting ??= Setting::query()
        ->where('key', $key)
        ->whereNull('organization_id')
        ->first();

    return $setting ? $setting->getTypedValue() : $default;
}
```

Usage:

```php
setting('app.name');                           // "Platform Core"
setting('notifications.email.enabled', true); // true (default if not set)
setting('app.timezone', 'UTC');
```

---

## Default Settings (Seeded)

| Key | Type | Default Value | Scope |
|-----|------|---------------|-------|
| `app.name` | string | `"Platform Core"` | global |
| `app.timezone` | string | `"UTC"` | global |
| `app.locale` | string | `"en"` | global |
| `notifications.email.enabled` | boolean | `true` | global |
| `notifications.email.from_name` | string | `"Platform Core"` | global |
| `auth.two_factor.enabled` | boolean | `false` | global |
| `auth.session.lifetime` | integer | `120` | global |
| `organizations.allow_subdomains` | boolean | `false` | global |

---

## Routes

### Web

| Method | URI | Component | Route Name |
|--------|-----|-----------|------------|
| GET | `/settings` | `GeneralSettings` | `settings.general` |
| GET | `/settings/appearance` | `AppearanceSettings` | `settings.appearance` |
| GET | `/settings/notifications` | `NotificationSettings` | `settings.notifications` |

---

## Permissions

| Permission String | Description |
|-------------------|-------------|
| `core.settings.manage` | View and update platform settings |

---

## Livewire Screens

### `GeneralSettings`
Form for `app.name`, `app.timezone`, `app.locale`. Organization admins see this for their scoped overrides.

### `AppearanceSettings`
Controls logo, color theme, and custom CSS. Backed by settings keys under `appearance.*`.

### `NotificationSettings`
Toggle email/in-app channels. Configure `notifications.email.*` keys.

---

## Module Settings Registration

Modules declare their settings in a registration array (similar to Menu):

```php
// In NotificationsServiceProvider::boot()
app(SettingsRegistrar::class)->register([
    ['key' => 'notifications.email.enabled',   'type' => 'boolean', 'default' => true],
    ['key' => 'notifications.email.from_name', 'type' => 'string',  'default' => 'Platform Core'],
]);
```

This ensures settings are seeded and appear in the settings UI grouped by module.

---

## Dependencies

- Organizations (for org-scoped overrides)

---

## Next Improvements

- Settings schema validation (allowed values, min/max for integers)
- Settings export/import (JSON)
- Settings change audit via AuditLog module
- Cached settings resolution (avoid N queries per request)
