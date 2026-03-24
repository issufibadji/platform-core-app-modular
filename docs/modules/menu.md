# Module: Menu

**Path:** `Modules/Core/Menu/` (planned)
**Alias:** `menu`
**Priority:** 50
**Status:** Planned

---

## Purpose

Provides a dynamic, database-backed navigation menu that reflects the current user's permissions. App modules register their own menu items during boot; the Menu module assembles and renders the final sidebar.

---

## Database Tables

### `menu_items`

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigint unsigned` | Primary key |
| `label` | `varchar(255)` | Display text |
| `icon` | `varchar(100)\|null` | Icon identifier (e.g., Heroicon name) |
| `route` | `varchar(255)\|null` | Named Laravel route |
| `url` | `varchar(255)\|null` | External URL (if not a route) |
| `permission` | `varchar(255)\|null` | Required permission to see this item |
| `parent_id` | `bigint unsigned\|null` | FK → menu_items.id (nesting) |
| `module` | `varchar(100)` | Which module registered this item |
| `order` | `int` | Sort order within the same parent/group |
| `is_active` | `boolean` | Whether the item is shown at all |
| `created_at` | `timestamp` | |
| `updated_at` | `timestamp` | |

---

## Models

### `MenuItem`

```php
namespace Modules\Core\Menu\Models;

class MenuItem extends Model
{
    protected $fillable = ['label', 'icon', 'route', 'url', 'permission', 'parent_id', 'module', 'order', 'is_active'];

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }
}
```

---

## Menu Resolution

The `MenuResolver` service builds the visible menu for the current user:

```php
class MenuResolver
{
    public function forUser(User $user): Collection
    {
        return MenuItem::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->filter(fn ($item) => $this->userCanSee($user, $item))
            ->map(fn ($item) => $item->load('children'));
    }

    private function userCanSee(User $user, MenuItem $item): bool
    {
        if (! $item->permission) return true;
        return $user->can($item->permission);
    }
}
```

---

## Module Menu Registration

Modules register menu items in their service provider:

```php
// In OrganizationsServiceProvider::boot()
app(MenuRegistrar::class)->add([
    'label'      => 'Organizations',
    'icon'       => 'building-office',
    'route'      => 'organizations.index',
    'permission' => 'core.organizations.view',
    'module'     => 'organizations',
    'order'      => 10,
]);
```

The `MenuRegistrar` writes to the `menu_items` table (or a runtime registry) during boot.

---

## Livewire Components

### `AppSidebar`

Renders the full sidebar navigation. Uses `MenuResolver` to get the user's visible menu. Highlights the active route.

```php
class AppSidebar extends Component
{
    public Collection $items;

    public function mount(): void
    {
        $this->items = app(MenuResolver::class)->forUser(auth()->user());
    }

    public function render(): View
    {
        return view('menu::components.app-sidebar');
    }
}
```

---

## Routes

### Web

| Method | URI | Component | Route Name |
|--------|-----|-----------|------------|
| GET | `/admin/menu` | `ManageMenu` | `menu.manage` |

---

## Permissions

| Permission String | Description |
|-------------------|-------------|
| `core.menu.manage` | Reorder and toggle menu items |

---

## Dependencies

- Permissions module (for permission-gated visibility)

---

## Next Improvements

- Drag-and-drop reordering in the admin UI
- Mega menu / multi-column layouts
- Breadcrumb generation from active menu item path
- Per-organization menu customization
