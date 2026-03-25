# Files Module

**Path:** `Modules/Files/`
**Status:** Live
**Priority:** 18

---

## Purpose

Platform-level file upload and attachment foundation. Provides metadata tracking for uploaded files and a polymorphic attachment system.

---

## Database

### `files` table

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| organization_id | bigint nullable | FK → organizations |
| disk | string | `local` or `public` (or custom) |
| path | string | Storage-relative path |
| original_name | string | Client-provided filename |
| mime_type | string nullable | MIME type |
| extension | string(20) nullable | File extension |
| size | bigint nullable | File size in bytes |
| visibility | string(20) | `private` or `public` |
| uploaded_by | bigint nullable | FK → users |
| attachable_type | string nullable | Polymorphic type |
| attachable_id | bigint nullable | Polymorphic ID |
| created_at / updated_at | timestamps | |

---

## Model

`Modules\Files\Models\File`

- `url()` — returns `Storage::disk($disk)->url($path)`
- `humanSize()` — returns human-readable size (B / KB / MB)
- `uploader()` — belongs to `App\Models\User`
- `organization()` — belongs to `Modules\Organizations\Models\Organization`
- `attachable()` — polymorphic `morphTo()`
- Scope: `search($term)`

---

## Service

`Modules\Files\Services\FileService`

```php
use Modules\Files\Services\FileService;

$service = app(FileService::class);

// Upload and store
$file = $service->store(
    upload: $request->file('document'),
    disk: 'local',
    directory: 'contracts',
    organizationId: $org->id,
    visibility: 'private',
);

// Attach to an entity
$service->attachTo($file, $organization);

// Delete (removes from storage + DB)
$service->delete($file);
```

---

## Routes

| Name | Method | URL | Description |
| --- | --- | --- | --- |
| `core.files.index` | GET | `/core/files` | List all files |
| `core.files.upload` | GET | `/core/files/upload` | Upload form |

---

## Livewire Components

| Component | Tag | Description |
| --- | --- | --- |
| `ListFiles` | `files.list-files` | Paginated list with search and delete |
| `UploadFile` | `files.upload-file` | Upload form with disk / directory / visibility |

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.files.view` | View the file list |
| `core.files.upload` | Upload new files |

---

## Storage Setup

For public file web access, run once after deployment:

```bash
php artisan storage:link
```

This creates a symlink from `public/storage` → `storage/app/public`.

---

## Limitations / Next Steps

- No download route — add a signed URL or stream controller as needed
- No file type allow-list beyond `max:10240` (10 MB)
- No per-organization scoped listing in the UI
- No S3 / cloud disk configuration yet
