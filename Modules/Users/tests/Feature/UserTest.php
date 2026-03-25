<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Model
// ---------------------------------------------------------------------------

describe('User model', function () {
    it('can be created via factory', function () {
        $user = User::factory()->create();

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->name)->not->toBeEmpty()
            ->and($user->email)->not->toBeEmpty();
    });

    it('has organizations relationship', function () {
        $user = User::factory()->create();

        expect(method_exists($user, 'organizations'))->toBeTrue();
    });

    it('can be assigned a role', function () {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

        $user->assignRole($role);

        expect($user->hasRole('editor'))->toBeTrue();
    });

    it('can sync roles', function () {
        $user = User::factory()->create();
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'editor', 'guard_name' => 'web']);

        $user->assignRole('admin');
        $user->syncRoles(['editor']);

        expect($user->hasRole('editor'))->toBeTrue()
            ->and($user->hasRole('admin'))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// Routes
// ---------------------------------------------------------------------------

describe('User routes', function () {
    it('redirects unauthenticated users from index', function () {
        $this->get(route('core.users.index'))
            ->assertRedirect(route('login'));
    });

    it('shows users index to authenticated users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('core.users.index'))
            ->assertOk();
    });

    it('shows create form to authenticated users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('core.users.create'))
            ->assertOk();
    });

    it('shows edit form to authenticated users', function () {
        $actor  = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($actor)
            ->get(route('core.users.edit', $target))
            ->assertOk();
    });

    it('redirects unauthenticated users from create', function () {
        $this->get(route('core.users.create'))
            ->assertRedirect(route('login'));
    });
});
