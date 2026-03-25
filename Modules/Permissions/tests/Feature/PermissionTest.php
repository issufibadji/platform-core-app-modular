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

describe('Permission model', function () {
    it('can be created with a name', function () {
        $permission = Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);

        expect($permission)->toBeInstanceOf(Permission::class)
            ->and($permission->name)->toBe('core.users.view');
    });

    it('can be assigned to a role', function () {
        $role       = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);

        $role->givePermissionTo($permission);

        expect($role->hasPermissionTo('core.users.view'))->toBeTrue();
    });

    it('can be checked on a user via role', function () {
        $user       = User::factory()->create();
        $role       = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);

        $role->givePermissionTo($permission);
        $user->assignRole($role);

        expect($user->can('core.users.view'))->toBeTrue();
    });

    it('denies permission not assigned', function () {
        $user = User::factory()->create();

        Permission::create(['name' => 'core.users.delete', 'guard_name' => 'web']);

        expect($user->can('core.users.delete'))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// Routes
// ---------------------------------------------------------------------------

describe('Permission routes', function () {
    it('redirects unauthenticated users from index', function () {
        $this->get(route('core.permissions.index'))
            ->assertRedirect(route('login'));
    });

    it('shows permissions index to authenticated users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('core.permissions.index'))
            ->assertOk();
    });
});
