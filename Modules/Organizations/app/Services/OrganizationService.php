<?php

namespace Modules\Organizations\Services;

use App\Models\User;
use Modules\Organizations\Models\Organization;

class OrganizationService
{
    public function create(array $data, ?User $owner = null): Organization
    {
        $organization = Organization::create([
            'name'     => $data['name'],
            'slug'     => $data['slug'] ?? null,
            'status'   => $data['status'] ?? 'active',
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            'locale'   => $data['locale'] ?? null,
        ]);

        if ($owner) {
            $organization->users()->attach($owner->id, [
                'is_owner'  => true,
                'status'    => 'active',
                'joined_at' => now(),
            ]);
        }

        return $organization;
    }

    public function update(Organization $organization, array $data): Organization
    {
        $organization->update([
            'name'     => $data['name'],
            'slug'     => $data['slug'] ?? $organization->slug,
            'status'   => $data['status'] ?? $organization->status->value,
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            'locale'   => $data['locale'] ?? null,
        ]);

        return $organization->fresh();
    }

    public function delete(Organization $organization): void
    {
        $organization->delete();
    }
}
