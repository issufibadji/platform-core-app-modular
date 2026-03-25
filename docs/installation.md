# Installation & Setup

## Requirements

| Requirement | Version | Notes |
|-------------|---------|-------|
| PHP | ^8.3 | |
| Composer | ^2.x | |
| Node.js | ^18.x or ^22.x | Node 22.7+ works; Vite 8+ requires Node 22.12+ |
| SQLite or MySQL | any recent | SQLite default for local dev |

> **Node version note:** The project uses Vite 6.x with `@tailwindcss/vite` v4. Node 22.7 is confirmed working. Do **not** upgrade Vite to v8+ unless Node is upgraded to 22.12+.

## 1. Clone and Install Dependencies

```bash
git clone <repo-url> platform-core-app-modular
cd platform-core-app-modular

composer install
npm install
```

`wikimedia/composer-merge-plugin` is configured to merge all `Modules/*/composer.json` files into the root autoloader automatically.

## 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

**Default `.env` values (development):**

```env
APP_NAME="Platform Core"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite  (defaults to database/database.sqlite)

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
```

For **MySQL** (production or preferred local):

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

## 4. Install spatie/laravel-permission

The package is declared in `composer.json`. After `composer install`, publish its migration and config:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

This creates the `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, and `role_has_permissions` tables.

## 5. Seed Initial Data

```bash
php artisan db:seed
```

The `DatabaseSeeder` calls `RolesAndPermissionsSeeder`, which:

- Creates 15 permissions (`core.organizations.*`, `core.users.*`, `core.roles.*`, `core.permissions.*`, `core.menu.view`)
- Creates 5 roles: `super-admin`, `owner`, `manager`, `operator`, `viewer`
- Assigns the `super-admin` role to the first user in the database

> Run `php artisan db:seed --class=RolesAndPermissionsSeeder` at any time to re-sync. The seeder is idempotent (`firstOrCreate`).

## 6. Build Assets

```bash
npm run build       # production build
npm run dev         # Vite watch mode
```

## 7. Start the Development Server

```bash
composer run dev
```

This starts concurrently:

- `php artisan serve` — PHP dev server on `http://localhost:8000`
- `npm run dev` — Vite asset watcher
- `php artisan queue:listen` — queue worker
- `php artisan pail` — real-time log viewer

## 8. Verify Modules Are Active

```bash
php artisan module:list
```

Expected output shows all modules as `Enabled`:

```text
+---------------+---------+----------+
| Name          | Status  | Priority |
+---------------+---------+----------+
| Core          | Enabled | 0        |
| Organizations | Enabled | 0        |
| Users         | Enabled | 10       |
| Roles         | Enabled | 20       |
| Permissions   | Enabled | 21       |
| Menu          | Enabled | 30       |
| SharedUI      | Enabled | 40       |
+---------------+---------+----------+
```

If a module shows `Disabled`:

```bash
php artisan module:enable ModuleName
```

## Common Setup Issues

### `Class not found` after adding a module

```bash
composer dump-autoload -o
```

### Migrations from modules not running

Check `modules_statuses.json` at the project root — all modules must be `true`.

### Assets not updating

```bash
php artisan optimize:clear
npm run build
```

### `ViteManifestNotFoundException`

Vite hasn't built yet. Run `npm run build` (production) or `composer run dev` (development).

### `@tailwindcss/vite: Cannot convert undefined or null to object`

This happens when `@tailwindcss/vite@4.1.x` is installed with Vite 5. The project requires Vite 6:

```bash
npm install vite@^6.3.0 --save-dev
npm run dev
```

### Roles/Permissions tables missing

Run the spatie migration step from section 4 above:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### SQLite database file not found

```bash
touch database/database.sqlite
```
