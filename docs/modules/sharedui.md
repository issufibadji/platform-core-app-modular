# Module: SharedUI

**Path:** `Modules/SharedUI/`
**Alias:** `sharedui`
**Priority:** 40
**Status:** ✅ Live

---

## Purpose

Pure UI module — no database tables, no business logic. Provides a library of reusable anonymous Blade components that all other modules use for their screens. Enforces visual and structural consistency across the platform.

---

## No Database Tables

SharedUI has no migrations.

---

## Component Registration

`SharedUIServiceProvider` registers components as anonymous components under the `ui` prefix:

```php
public function boot(): void
{
    parent::boot();
    Blade::anonymousComponentPath(
        module_path('SharedUI', '/resources/views/components'),
        'ui'
    );
}
```

Usage in any Blade view: `<x-ui::component-name />` (double colon for anonymous component namespaces).

---

## Available Components

### `<x-ui::page-header>`

Page title bar with optional description, back button, and actions slot.

**Props:**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `title` | string | required | Page heading |
| `description` | string\|null | null | Subtitle below title |
| `backRoute` | string\|null | null | Named route for back button |
| `backLabel` | string | `'Back'` | Back button label |

**Slots:** `$actions` — rendered as a flex row on the right.

**Example:**

```blade
<x-ui::page-header title="Organizations" description="Manage your organizations">
    <x-slot:actions>
        <flux:button href="{{ route('core.organizations.create') }}" wire:navigate>New</flux:button>
    </x-slot:actions>
</x-ui::page-header>
```

---

### `<x-ui::card>`

Bordered content card.

**Props:**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `title` | string\|null | null | Optional card heading |
| `padding` | string | `'p-6'` | Tailwind padding class |

**Example:**

```blade
<x-ui::card title="Details">
    <p>Content here</p>
</x-ui::card>
```

---

### `<x-ui::empty-state>`

Centered empty-state message for empty lists.

**Props:**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `title` | string | required | Primary message |
| `description` | string\|null | null | Secondary text |

**Slots:** `$actions` — optional CTA below the description.

**Example:**

```blade
<x-ui::empty-state title="No organizations yet" description="Create your first organization to get started.">
    <x-slot:actions>
        <flux:button href="{{ route('core.organizations.create') }}" wire:navigate>Create</flux:button>
    </x-slot:actions>
</x-ui::empty-state>
```

---

### `<x-ui::alert>`

Inline alert banner with four severity levels.

**Props:**

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `type` | string | `'info'` | `info`, `success`, `warning`, `error` |
| `message` | string\|null | null | Alert text (or use default slot) |

**Example:**

```blade
<x-ui::alert type="success" message="Organization saved successfully." />
<x-ui::alert type="error">Something went wrong.</x-ui::alert>
```

---

## Component Source Files

All components live in `Modules/SharedUI/resources/views/components/`:

| File | Component tag |
| --- | --- |
| `page-header.blade.php` | `<x-ui::page-header>` |
| `card.blade.php` | `<x-ui::card>` |
| `empty-state.blade.php` | `<x-ui::empty-state>` |
| `alert.blade.php` | `<x-ui::alert>` |

---

## Styling

Components use Tailwind CSS utility classes and `flux:*` components (Livewire Flux) for headings and text. Dark mode is supported via `dark:` variants throughout.

---

## Dependencies

- Livewire Flux (`flux:heading`, `flux:text` components used internally)
- Tailwind CSS v4

---

## Next Improvements

- Data table component (sortable, paginated)
- Modal / confirmation dialog component
- Toast notification component
- Form field components (input, select, toggle)
- Loading skeleton component
