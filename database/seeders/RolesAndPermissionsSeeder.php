<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /** Idempotent — safe to run multiple times. */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─────────────────────────────────────────────────
        // Permissions
        // ─────────────────────────────────────────────────
        $permissions = [
            // Organizations
            'core.organizations.view',
            'core.organizations.create',
            'core.organizations.update',
            'core.organizations.delete',

            // Users
            'core.users.view',
            'core.users.create',
            'core.users.update',
            'core.users.delete',

            // Roles
            'core.roles.view',
            'core.roles.create',
            'core.roles.update',
            'core.roles.delete',

            // Permissions
            'core.permissions.view',
            'core.permissions.create',

            // Menu
            'core.menu.view',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // ─────────────────────────────────────────────────
        // Roles
        // ─────────────────────────────────────────────────
        $superAdmin  = Role::firstOrCreate(['name' => 'super-admin',  'guard_name' => 'web']);
        $owner       = Role::firstOrCreate(['name' => 'owner',        'guard_name' => 'web']);
        $manager     = Role::firstOrCreate(['name' => 'manager',      'guard_name' => 'web']);
        $operator    = Role::firstOrCreate(['name' => 'operator',     'guard_name' => 'web']);
        $viewer      = Role::firstOrCreate(['name' => 'viewer',       'guard_name' => 'web']);

        // super-admin gets everything
        $superAdmin->syncPermissions(Permission::all());

        // owner gets org + user management
        $owner->syncPermissions([
            'core.organizations.view', 'core.organizations.create',
            'core.organizations.update', 'core.organizations.delete',
            'core.users.view', 'core.users.create', 'core.users.update',
            'core.roles.view', 'core.permissions.view', 'core.menu.view',
        ]);

        // manager gets most of owner minus delete
        $manager->syncPermissions([
            'core.organizations.view', 'core.organizations.update',
            'core.users.view', 'core.users.create', 'core.users.update',
            'core.roles.view', 'core.permissions.view', 'core.menu.view',
        ]);

        // operator gets view + create
        $operator->syncPermissions([
            'core.organizations.view',
            'core.users.view', 'core.users.create',
            'core.menu.view',
        ]);

        // viewer gets view only
        $viewer->syncPermissions([
            'core.organizations.view',
            'core.users.view',
            'core.menu.view',
        ]);

        $this->command->info('✔  Roles and permissions seeded successfully.');

        // ─────────────────────────────────────────────────
        // Optional: assign super-admin to first user
        // ─────────────────────────────────────────────────
        $first = User::first();
        if ($first && ! $first->hasRole('super-admin')) {
            $first->assignRole('super-admin');
            $this->command->info("✔  super-admin role assigned to [{$first->email}].");
        }
    }
}
