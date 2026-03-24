# Installation & Setup

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | ^8.3 |
| Composer | ^2.x |
| Node.js | ^20.x |
| SQLite / MySQL | any recent |

## 1. Clone and Install Dependencies

```bash
git clone <repo-url> platform-core-app-modular
cd platform-core-app-modular

composer install
npm install
```

The `wikimedia/composer-merge-plugin` is configured to automatically merge all `Modules/*/composer.json` files into the root autoloader. This means module-level dependencies are picked up automatically.

## 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### Default `.env` values (development)

The project ships with SQLite as the default database driver:

```env
APP_NAME="Platform Core"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite  # defaults to database/database.sqlite

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
```

For **MySQL** (production or preferred local), replace the DB block:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=platform_core
DB_USERNAME=root
DB_PASSWORD=secret
```

## 3. Database Setup

### SQLite (default)

```bash
touch database/database.sqlite
php artisan migrate
```

### MySQL

Create the database first, then:

```bash
php artisan migrate
```

### Seed initial data

```bash
php artisan db:seed
```

> Module-specific seeders are discovered automatically when modules register their `DatabaseSeeder` classes.

## 4. Storage Link

Required for file uploads and public storage access:

```bash
php artisan storage:link
```

## 5. Build Assets

```bash
npm run build       # production build
npm run dev         # watch mode for development
```

## 6. Start the Development Server

The project ships with a `composer run dev` script that starts all required processes concurrently:

```bash
composer run dev
```

This runs:
- `php artisan serve` — PHP dev server on `http://localhost:8000`
- `npm run dev` — Vite asset watcher
- `php artisan queue:listen` — queue worker for jobs/notifications
- `php artisan pail` — real-time log viewer (Laravel Pail)

## 7. Installing nwidart/laravel-modules

Already included in `composer.json`. After a fresh `composer install`, the package is ready. To verify modules are recognized:

```bash
php artisan module:list
```

Expected output shows `Core` and `Organizations` as enabled.

### Module configuration

The modules configuration lives at `config/modules.php` (published by the package). The default module root is `Modules/` in the project root.

## 8. Livewire & Flux

Livewire 4.1 and Livewire Flux 2.12 are installed as Composer packages. No manual publishing is required for basic usage.

For Flux component styles, Tailwind is configured to scan Flux's vendor views:

```js
// vite.config.js / tailwind.config.js
content: [
    './vendor/livewire/flux/stubs/**/*.blade.php',
    './Modules/**/resources/views/**/*.blade.php',
    ...
]
```

## 9. Two-Factor Authentication

The project includes Fortify with 2FA columns. The migration `add_two_factor_columns_to_users_table` is included in the base `database/migrations/` folder and runs with the standard `php artisan migrate`.

## Common Setup Issues

### `Class not found` after adding a module

Run:
```bash
composer dump-autoload -o
```

The merge plugin registers module autoloads, but this command ensures everything is indexed.

### Migrations from modules not running

Ensure modules are enabled:
```bash
php artisan module:list
```

If a module shows as `Disabled`, enable it:
```bash
php artisan module:enable ModuleName
```

### Assets not updating

Clear all caches and rebuild:
```bash
php artisan optimize:clear
npm run build
```

### Storage symlink already exists

```bash
php artisan storage:link --force
```

### SQLite database file not found

```bash
touch database/database.sqlite
```

The default `DB_DATABASE` path in `.env.example` points to this relative path.
