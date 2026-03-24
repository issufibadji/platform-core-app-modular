# Module Structure

Every module under `Modules/` follows the same internal structure. This consistency makes it easier to navigate unfamiliar modules and apply tooling uniformly.

## Full Structure

```
Modules/Core/Organizations/
├── app/
│   ├── Actions/
│   ├── DTOs/
│   ├── Enums/
│   ├── Events/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Livewire/
│   │   └── Requests/
│   ├── Models/
│   ├── Policies/
│   ├── Providers/
│   ├── Queries/
│   └── Services/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
├── routes/
│   ├── web.php
│   └── api.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── composer.json
└── module.json
```

## Directory Descriptions

### `app/Actions/`

Single-purpose classes that execute one operation. Actions encapsulate a task that is too complex for a controller but too simple to justify a full service. Each action has a single public `handle()` or `execute()` method.

```php
// Example: Modules/Core/Organizations/app/Actions/CreateOrganizationAction.php
class CreateOrganizationAction
{
    public function handle(CreateOrganizationDTO $dto): Organization
    {
        // validation, creation, event dispatch
    }
}
```

### `app/DTOs/`

Data Transfer Objects. Typed value objects used to pass structured data into Actions and Services. DTOs replace untyped arrays and make method signatures explicit.

```php
class CreateOrganizationDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $domain = null,
    ) {}
}
```

### `app/Enums/`

PHP 8.1+ backed enums for domain constants. Avoid magic strings scattered through the codebase.

```php
enum OrganizationStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';
}
```

### `app/Events/`

Domain events dispatched when something significant happens. Other modules or listeners can subscribe without coupling to the source module.

```php
class OrganizationCreated
{
    public function __construct(public Organization $organization) {}
}
```

### `app/Http/Controllers/`

Standard Laravel controllers for web and API routes. Keep controllers thin — delegate to Actions or Services.

### `app/Http/Livewire/`

Livewire components for this module's UI screens. Each component corresponds to a full page or significant interactive section.

```
Livewire/
├── Organizations/
│   ├── ListOrganizations.php
│   ├── CreateOrganization.php
│   └── EditOrganization.php
```

### `app/Http/Requests/`

Form request classes for validation. One request class per form submission.

### `app/Models/`

Eloquent models specific to this module. Models should be lean: relationships, scopes, casts, and accessors only. No business logic.

### `app/Policies/`

Laravel authorization policies. One policy per model. Used by `Gate::authorize()` and Livewire component guards.

```php
class OrganizationPolicy
{
    public function view(User $user, Organization $org): bool { ... }
    public function create(User $user): bool { ... }
    public function update(User $user, Organization $org): bool { ... }
    public function delete(User $user, Organization $org): bool { ... }
}
```

### `app/Providers/`

The module's service provider(s). Registers routes, migrations, views, event listeners, and policy bindings. The primary provider is named `[Module]ServiceProvider`.

### `app/Queries/`

Query builder classes that encapsulate complex or reusable database queries. Keeps controllers and Livewire components from accumulating query logic.

```php
class OrganizationQuery
{
    public function active(): Builder
    {
        return Organization::query()->where('status', OrganizationStatus::Active);
    }
}
```

### `app/Services/`

Business logic that spans multiple models or requires external integrations. Services are stateless and injectable.

### `config/`

Module-specific configuration file, typically named after the module alias:

```php
// config/organizations.php
return [
    'allow_subdomains' => env('ORGANIZATIONS_ALLOW_SUBDOMAINS', false),
];
```

Access via: `config('organizations.allow_subdomains')`

### `database/migrations/`

Migrations scoped to this module's tables. Loaded automatically when the module is enabled.

Migration naming: `YYYY_MM_DD_HHMMSS_create_organizations_table.php`

### `database/seeders/`

Seeders for development and test data. The module's main seeder is typically named `[Module]DatabaseSeeder`.

### `resources/views/`

Blade templates for this module. Organized by feature area:

```
views/
├── organizations/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── components/
    └── org-badge.blade.php
```

Views are namespaced by module alias. Reference them as `organizations::organizations.index`.

### `routes/web.php`

Web routes for this module. Should apply `auth` and `verified` middleware for all protected routes.

```php
Route::middleware(['auth', 'verified'])->prefix('organizations')->name('organizations.')->group(function () {
    Route::get('/', ListOrganizations::class)->name('index');
    Route::get('/create', CreateOrganization::class)->name('create');
    Route::get('/{organization}/edit', EditOrganization::class)->name('edit');
});
```

### `routes/api.php`

API routes, versioned under `api/v1/`. Apply `auth:sanctum` middleware.

```php
Route::middleware('auth:sanctum')->prefix('v1/organizations')->name('api.organizations.')->group(function () {
    Route::apiResource('/', OrganizationController::class);
});
```

### `tests/`

Pest tests split into `Feature/` and `Unit/`. Feature tests cover HTTP and Livewire interactions. Unit tests cover Actions, Services, and DTOs in isolation.

### `composer.json`

Module-level Composer file. Merged into the root autoloader by `wikimedia/composer-merge-plugin`. Define module-specific dependencies and autoload rules here.

```json
{
    "name": "modules/organizations",
    "autoload": {
        "psr-4": {
            "Modules\\Organizations\\": "app/",
            "Modules\\Organizations\\Database\\Factories\\": "database/factories/"
        }
    }
}
```

### `module.json`

The nwidart/laravel-modules manifest. Controls module name, alias, priority, and service providers.

```json
{
    "name": "Organizations",
    "alias": "organizations",
    "description": "Manages organizations and tenancy context",
    "priority": 10,
    "providers": [
        "Modules\\Organizations\\Providers\\OrganizationsServiceProvider"
    ],
    "files": []
}
```
