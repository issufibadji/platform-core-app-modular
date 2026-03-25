# Commands Reference

## Module Management

### `php artisan module:list`

Lists all registered modules with their status (Enabled/Disabled).

```bash
php artisan module:list
```

**Use when:** Verifying that a newly created module is recognized, or checking which modules are active before running migrations.

---

### `php artisan module:make [ModuleName]`

Scaffolds a new module under `Modules/[ModuleName]/` with the default structure.

```bash
php artisan module:make Organizations
php artisan module:make --plain Organizations   # skip default stub files
```

**Use when:** Starting a new module. After scaffolding, move it to the correct path (e.g., `Modules/Core/Organizations/`) if it should be part of the Core family.

> Note: The default scaffold may generate more boilerplate than needed. Review and trim generated files to match the project's actual structure.

---

### `php artisan module:enable [ModuleName]`

Enables a disabled module so it is loaded on boot.

```bash
php artisan module:enable Organizations
```

---

### `php artisan module:disable [ModuleName]`

Disables a module without deleting it.

```bash
php artisan module:disable FeatureFlags
```

**Use when:** Temporarily deactivating a module in an environment (e.g., disabling Dashboard in a staging environment).

---

### `php artisan module:migrate [ModuleName]`

Runs migrations for a specific module only.

```bash
php artisan module:migrate Organizations
php artisan module:migrate --force Organizations   # force in production
```

**Use when:** You've added a new migration to a module and want to run it in isolation without touching other modules or the base migrations.

---

### `php artisan module:seed [ModuleName]`

Runs the seeder for a specific module.

```bash
php artisan module:seed Organizations
```

---

### `php artisan module:make-migration [migration_name] --module=[ModuleName]`

Creates a migration file inside a module's `database/migrations/` directory.

```bash
php artisan module:make-migration create_organizations_table --module=Organizations
php artisan module:make-migration add_domain_to_organizations_table --module=Organizations
```

---

### `php artisan module:make-model [ModelName] --module=[ModuleName]`

Generates a model inside the specified module.

```bash
php artisan module:make-model Organization --module=Organizations
```

---

### `php artisan module:make-livewire [ComponentName] --module=[ModuleName]`

Generates a Livewire component inside the specified module.

```bash
php artisan module:make-livewire Organizations/ListOrganizations --module=Organizations
```

---

## Database

### `php artisan migrate`

Runs all pending migrations from the base `database/migrations/` and all enabled modules.

```bash
php artisan migrate
php artisan migrate --fresh          # drop all tables and re-run
php artisan migrate --fresh --seed   # re-run and seed
```

**Use when:** After pulling new code that includes migrations, or setting up a fresh environment.

---

### `php artisan db:seed`

Runs all registered seeders.

```bash
php artisan db:seed
php artisan db:seed --class=RolesAndPermissionsSeeder
```

The `RolesAndPermissionsSeeder` is idempotent — safe to run multiple times. It creates 5 roles and 15 `core.*` permissions using `firstOrCreate`.

---

### `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`

Publishes spatie/laravel-permission config and migration.

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

**Use when:** Setting up the project for the first time. Required before `db:seed` will work for roles and permissions.

---

## Application Cache

### `php artisan optimize:clear`

Clears all cached files: config, routes, views, events, and compiled classes.

```bash
php artisan optimize:clear
```

**Use when:** Config or route changes are not taking effect, or after pulling code with modified `.env` or config files.

---

### `php artisan config:cache`

Compiles all config files into a single cached file for production performance.

```bash
php artisan config:cache
```

> Do **not** use in development — it prevents `.env` changes from taking effect without re-caching.

---

### `php artisan route:cache`

Caches all routes for faster resolution in production.

```bash
php artisan route:cache
```

> Do **not** use in development — new routes won't be recognized until cache is cleared.

---

## Autoloading

### `composer dump-autoload -o`

Regenerates the Composer autoloader with optimized class maps.

```bash
composer dump-autoload -o
```

**Use when:**
- A new module's `composer.json` was added or modified.
- A class is not found despite existing in the expected location.
- After manually moving or renaming module directories.

The `-o` flag generates an optimized (classmap) autoloader. Omit it in development if the rebuild is slow.

---

## Storage

### `php artisan storage:link`

Creates a symbolic link from `public/storage` to `storage/app/public`.

```bash
php artisan storage:link
php artisan storage:link --force   # overwrite existing symlink
```

**Use when:** Setting up a new environment. Required for any file uploads that should be publicly accessible.

---

## Development Server

### `composer run dev`

Starts all development processes concurrently using the `dev` script defined in `composer.json`.

```bash
composer run dev
```

Runs:
- `php artisan serve` — web server at `http://localhost:8000`
- `npm run dev` — Vite watcher for assets
- `php artisan queue:listen` — queue worker
- `php artisan pail` — real-time log viewer

**Use when:** Starting a local development session. This replaces running each process manually.

---

## Code Quality

### `./vendor/bin/pint`

Runs Laravel Pint (PHP CS Fixer) to auto-format code.

```bash
./vendor/bin/pint                  # fix all files
./vendor/bin/pint --test           # check without fixing
./vendor/bin/pint Modules/Organizations/
```

---

### `./vendor/bin/pest`

Runs the Pest test suite.

```bash
./vendor/bin/pest
./vendor/bin/pest --filter OrganizationTest
./vendor/bin/pest Modules/Organizations/tests/
```

---

## Assets

### `npm run dev`

Starts the Vite development server with hot module replacement.

```bash
npm run dev
```

---

### `npm run build`

Compiles and minifies assets for production.

```bash
npm run build
```

**Use when:** Deploying to staging or production, or when testing the final compiled bundle locally.
