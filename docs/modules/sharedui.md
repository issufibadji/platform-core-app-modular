# Module: SharedUI

**Path:** `Modules/Core/SharedUI/` (planned)
**Alias:** `shared_ui`
**Priority:** 70
**Status:** Planned

---

## Purpose

SharedUI is a pure UI module — no database tables, no business logic. It provides the base authenticated layout and a library of reusable Blade/Livewire/Flux components that all other modules use for their screens.

Without SharedUI, every module would implement its own layout and duplicate table, modal, and form components. SharedUI enforces visual and structural consistency across the entire platform.

---

## No Database Tables

SharedUI has no migrations. It is composed entirely of Blade components, Livewire components, and layout files.

---

## Layouts

### `authenticated`

The primary authenticated layout. Wraps every admin screen.

```
┌─────────────────────────────────────────────┐
│  Topbar: Logo | Search | Notifications | User│
├──────────────┬──────────────────────────────┤
│              │                              │
│   Sidebar    │      Page Content Area       │
│   (Menu)     │      (slot)                  │
│              │                              │
└──────────────┴──────────────────────────────┘
```

Usage in a Livewire component:

```blade
<x-shared-ui::layouts.authenticated :title="'Organizations'">
    <x-shared-ui::page-header title="Organizations" :breadcrumbs="$breadcrumbs" />
    <!-- page content -->
</x-shared-ui::layouts.authenticated>
```

---

## Component Library

### Layout Components

| Component | Description |
|-----------|-------------|
| `<x-shared-ui::layouts.authenticated>` | Main page wrapper with sidebar + topbar |
| `<x-shared-ui::page-header>` | Page title + breadcrumbs + optional action button |
| `<x-shared-ui::card>` | Bordered content card with optional header/footer |

### Data Components

| Component | Description |
|-----------|-------------|
| `<x-shared-ui::table>` | Sortable, paginated data table |
| `<x-shared-ui::table.column>` | Column definition with optional sort |
| `<x-shared-ui::table.row>` | Table row with slot for cells |
| `<x-shared-ui::empty-state>` | Empty state illustration + message + CTA |
| `<x-shared-ui::badge>` | Colored status badge (Active, Suspended, etc.) |
| `<x-shared-ui::pagination>` | Paginator that wraps Laravel's paginator |

### Form Components

| Component | Description |
|-----------|-------------|
| `<x-shared-ui::input>` | Text input with label, error, and help text |
| `<x-shared-ui::select>` | Dropdown select |
| `<x-shared-ui::toggle>` | Boolean toggle switch |
| `<x-shared-ui::textarea>` | Multi-line text |
| `<x-shared-ui::date-picker>` | Date input with calendar popup |
| `<x-shared-ui::file-upload>` | File upload area with drag-and-drop |
| `<x-shared-ui::form-section>` | Groups related form fields with a heading |

### Feedback Components

| Component | Description |
|-----------|-------------|
| `<x-shared-ui::modal>` | Slide-over or centered modal dialog |
| `<x-shared-ui::confirm-dialog>` | Confirmation modal with destructive action warning |
| `<x-shared-ui::toast>` | Flash notification (success, error, warning) |
| `<x-shared-ui::alert>` | Inline alert banner |
| `<x-shared-ui::loading>` | Skeleton loading placeholder |

### Navigation Components

| Component | Description |
|-----------|-------------|
| `<x-shared-ui::app-sidebar>` | Sidebar using Menu module's resolver |
| `<x-shared-ui::topbar>` | Top navigation bar |
| `<x-shared-ui::breadcrumbs>` | Breadcrumb trail |
| `<x-shared-ui::tab-bar>` | Horizontal tab navigation within a page |

---

## Using Flux

SharedUI wraps [Livewire Flux](https://fluxui.dev/) components. Flux provides the low-level primitives (buttons, inputs, dropdowns); SharedUI adds platform-specific compositions and naming conventions on top.

Direct Flux usage in module views is acceptable for simple cases. SharedUI components are preferred when a pattern repeats across modules.

---

## Tailwind Configuration

The `tailwind.config.js` must scan SharedUI views:

```js
content: [
    './Modules/Core/SharedUI/resources/views/**/*.blade.php',
    './Modules/**/resources/views/**/*.blade.php',
    './vendor/livewire/flux/stubs/**/*.blade.php',
    // ...
]
```

---

## Service Provider Registration

```php
// Modules/Core/SharedUI/Providers/SharedUIServiceProvider.php

public function boot(): void
{
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'shared-ui');

    Blade::componentNamespace(
        'Modules\\Core\\SharedUI\\View\\Components',
        'shared-ui'
    );
}
```

---

## Dependencies

- Menu module (sidebar uses `MenuResolver`)
- No data dependencies

---

## Next Improvements

- Dark mode support across all components
- RTL (right-to-left) layout support
- Storybook or visual component catalogue
- Accessibility audit (ARIA labels, keyboard navigation)
- Print-friendly CSS for reports
