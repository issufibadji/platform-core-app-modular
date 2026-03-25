<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Menu\Services\MenuService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// MenuService
// ---------------------------------------------------------------------------

describe('MenuService', function () {
    it('returns empty array when no user is authenticated', function () {
        $service = app(MenuService::class);

        $menu = $service->forUser();

        expect($menu)->toBe([]);
    });

    it('returns groups for authenticated user with null-permission items', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $service = app(MenuService::class);

        // The 'Platform' group has Dashboard with permission = null
        $menu = $service->forUser();

        $groups = collect($menu)->pluck('group')->toArray();
        expect($groups)->toContain('Platform');
    });

    it('filters out items the user lacks permission for', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create the permissions so Spatie does NOT fall back to "show all"
        Permission::create(['name' => 'core.organizations.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.roles.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.permissions.view', 'guard_name' => 'web']);

        $service = app(MenuService::class);
        $menu    = $service->forUser();

        // User has no roles/permissions → Core and Access Control groups should be absent
        $groups = collect($menu)->pluck('group')->toArray();
        expect($groups)->not->toContain('Core')
            ->and($groups)->not->toContain('Access Control');
    });

    it('shows permission-gated items when user has the permission', function () {
        $user = User::factory()->create();

        $permission = Permission::create(['name' => 'core.organizations.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.roles.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.permissions.view', 'guard_name' => 'web']);

        $role = Role::create(['name' => 'viewer', 'guard_name' => 'web']);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        $this->actingAs($user);

        $service = app(MenuService::class);
        $menu    = $service->forUser();

        $coreGroup = collect($menu)->firstWhere('group', 'Core');
        expect($coreGroup)->not->toBeNull();

        $labels = collect($coreGroup['items'])->pluck('label')->toArray();
        expect($labels)->toContain('Organizations')
            ->and($labels)->not->toContain('Users');
    });

    it('returns all groups for user with super-admin role', function () {
        $user = User::factory()->create();

        Permission::create(['name' => 'core.organizations.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.users.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.roles.view', 'guard_name' => 'web']);
        Permission::create(['name' => 'core.permissions.view', 'guard_name' => 'web']);

        $role = Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        $role->syncPermissions([
            'core.organizations.view',
            'core.users.view',
            'core.roles.view',
            'core.permissions.view',
        ]);
        $user->assignRole($role);

        $this->actingAs($user);

        $service = app(MenuService::class);
        $menu    = $service->forUser();

        $groups = collect($menu)->pluck('group')->toArray();
        expect($groups)->toContain('Platform')
            ->and($groups)->toContain('Core')
            ->and($groups)->toContain('Access Control');
    });
});
