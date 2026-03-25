<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Organizations\Enums\OrganizationStatus;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Model
// ---------------------------------------------------------------------------

describe('Organization model', function () {
    it('generates a unique slug from name on create', function () {
        $org = Organization::create(['name' => 'Acme Corp', 'status' => 'active']);

        expect($org->slug)->toBe('acme-corp');
    });

    it('appends counter to slug when slug already exists', function () {
        Organization::create(['name' => 'Acme Corp', 'slug' => 'acme-corp', 'status' => 'active']);
        $org2 = Organization::create(['name' => 'Acme Corp', 'status' => 'active']);

        expect($org2->slug)->toBe('acme-corp-1');
    });

    it('casts status to OrganizationStatus enum', function () {
        $org = Organization::create(['name' => 'Test Org', 'status' => 'active']);

        expect($org->status)
            ->toBeInstanceOf(OrganizationStatus::class)
            ->toBe(OrganizationStatus::Active);
    });

    it('scopeActive filters only active orgs', function () {
        Organization::create(['name' => 'Active Org', 'status' => 'active']);
        Organization::create(['name' => 'Suspended Org', 'status' => 'suspended']);

        expect(Organization::active()->count())->toBe(1);
    });

    it('scopeSearch finds orgs by name', function () {
        Organization::create(['name' => 'Acme Corp', 'status' => 'active']);
        Organization::create(['name' => 'Beta Inc', 'status' => 'active']);

        expect(Organization::search('Acme')->count())->toBe(1);
    });
});

// ---------------------------------------------------------------------------
// Service
// ---------------------------------------------------------------------------

describe('OrganizationService', function () {
    it('creates an organization with default active status', function () {
        $service = app(OrganizationService::class);

        $org = $service->create(['name' => 'Test Corp']);

        expect($org)->toBeInstanceOf(Organization::class)
            ->and($org->name)->toBe('Test Corp')
            ->and($org->status)->toBe(OrganizationStatus::Active);
    });

    it('attaches owner when provided', function () {
        $user = User::factory()->create();
        $service = app(OrganizationService::class);

        $org = $service->create(['name' => 'Owned Corp'], $user);

        expect($org->users()->wherePivot('is_owner', true)->count())->toBe(1)
            ->and($org->owner()->id)->toBe($user->id);
    });

    it('soft-deletes an organization', function () {
        $service = app(OrganizationService::class);
        $org = $service->create(['name' => 'To Delete']);

        $service->delete($org);

        expect(Organization::find($org->id))->toBeNull()
            ->and(Organization::withTrashed()->find($org->id))->not->toBeNull();
    });
});

// ---------------------------------------------------------------------------
// Routes
// ---------------------------------------------------------------------------

describe('Organization routes', function () {
    it('redirects unauthenticated users from index', function () {
        $this->get(route('core.organizations.index'))
            ->assertRedirect(route('login'));
    });

    it('shows organizations index to authenticated users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('core.organizations.index'))
            ->assertOk();
    });

    it('shows create form to authenticated users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('core.organizations.create'))
            ->assertOk();
    });
});
