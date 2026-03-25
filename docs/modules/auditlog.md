# AuditLog Module

**Path:** `Modules/AuditLog/`
**Status:** Live
**Priority:** 16

---

## Purpose

Records administrative and sensitive platform actions to a persistent audit trail. Provides a read-only listing UI and a service for structured event logging.

---

## Database

### `audit_logs` table

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| user_id | bigint nullable | FK → users (null if system action) |
| event | string | e.g. `created`, `updated`, `deleted` |
| auditable_type | string nullable | Polymorphic model class |
| auditable_id | bigint nullable | Polymorphic model ID |
| old_values | json nullable | Previous attribute values |
| new_values | json nullable | New attribute values |
| url | string nullable | Request URL at time of action |
| ip_address | string(45) nullable | IPv4 or IPv6 |
| user_agent | text nullable | Request user agent |
| tags | json nullable | Free-form tags array |
| created_at / updated_at | timestamps | |

---

## Model

`Modules\AuditLog\Models\AuditEntry`

- `user()` — belongs to `App\Models\User`
- `auditable()` — polymorphic `morphTo()`
- Scopes: `forEvent($event)`, `search($term)`

---

## Service

`Modules\AuditLog\Services\AuditLogger`

```php
use Modules\AuditLog\Services\AuditLogger;

$logger = app(AuditLogger::class);

// Generic log
$logger->log('settings.updated', $setting, $old, $new);

// Convenience helpers
$logger->created($model);              // logs 'created' with all attributes
$logger->updated($model, $dirtyArr);   // logs 'updated' with old/new diff
$logger->deleted($model);              // logs 'deleted' with all attributes
```

---

## Integration Hooks

Add `AuditLogger` calls to service methods:

```php
// In OrganizationService::update()
app(AuditLogger::class)->updated($organization, $dirty);

// In SettingService::set()
app(AuditLogger::class)->log('settings.updated', $setting, $old, $new);
```

---

## Routes

| Name | Method | URL | Description |
| --- | --- | --- | --- |
| `core.auditlog.index` | GET | `/core/auditlog` | Browse audit entries |

---

## Livewire Components

| Component | Tag | Description |
| --- | --- | --- |
| `ListAuditLogs` | `auditlog.list-audit-logs` | Paginated log with event filter and search |

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.auditlog.view` | View the audit log |

---

## Limitations / Next Steps

- `AuditLogger` calls must be added manually to service methods — no automatic model observer hook yet
- No detail/show view per entry
- No export functionality
- Consider adding observers to `Organization`, `User`, and `Setting` models
