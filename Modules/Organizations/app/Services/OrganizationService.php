<?php

namespace Modules\Organizations\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\Organizations\Models\Organization;

class OrganizationService
{
    public function paginate(int $perPage = 15, string $search = ''): LengthAwarePaginator
    {
        return Organization::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Organization
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($data['name']);
        }

        return Organization::create($data);
    }

    public function update(Organization $organization, array $data): Organization
    {
        $organization->update($data);

        return $organization->fresh();
    }

    public function delete(Organization $organization): void
    {
        $organization->delete();
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = Organization::withTrashed()->where('slug', 'like', "{$slug}%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
