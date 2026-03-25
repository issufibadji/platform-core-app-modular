<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Model
// ---------------------------------------------------------------------------

describe('Role model', function () {
    it('can be created with a name', function () {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

        expect($role)->toBeInstanceOf(Role::class)
            ->and($role->name)->toBe('editor');
    });

    it('can sync permissions', function () {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.users.create', 'guard_name' => 'web']);

        $role->syncPermissions(['core.users.view', 'core.users.create']);

        expect($role->permissions)->toHaveCount(2);
    });

    it('can remove permissions on sync', function () {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.users.create', 'guard_name' => 'web']);

        $role->syncPermissions(['core.users.view', 'core.users.create']);
        $role->syncPermissions(['core.users.view']);

        expect($role->fresh()->permissions)->toHaveCount(1)
            ->and($role->fresh()->permissions->first()->name)->toBe('core.users.view');
    });

    it('can be assigned to a user', function () {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'manager', 'guard_name' => 'web']);

        $user->assignRole($role);

        expect($user->hasRole('manager'))->toBeTrue();
    });
});

// ---------------------------------------------------------------------------
// Routes
// ---------------------------------------------------------------------------

describe('Role routes', function () {
    it('redirects unauthenticated users from index', function () {
        $this->get(route('core.roles.index'))
            ->assertRedirect(route('login'));
    });

    it('shows roles index to authenticated users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('core.roles.index'))
            ->assertOk();
    });

    it('shows create form to authenticated users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('core.roles.create'))
            ->assertOk();
    });

    it('redirects unauthenticated users from create', function () {
        $this->get(route('core.roles.create'))
            ->assertRedirect(route('login'));
    });
});
