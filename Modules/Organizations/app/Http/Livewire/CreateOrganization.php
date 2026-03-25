<?php

namespace Modules\Organizations\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;

class CreateOrganization extends Component
{
    public ?Organization $organization = null;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('nullable|string|max:255')]
    public string $slug = '';

    #[Rule('nullable|email|max:255')]
    public string $email = '';

    #[Rule('nullable|string|max:50')]
    public string $phone = '';

    #[Rule('nullable|string|max:100')]
    public string $timezone = '';

    #[Rule('nullable|string|max:10')]
    public string $locale = '';

    public bool $slugManuallyEdited = false;

    public function mount(?Organization $organization = null): void
    {
        if ($organization && $organization->exists) {
            $this->organization = $organization;
            $this->name      = $organization->name;
            $this->slug      = $organization->slug;
            $this->email     = $organization->email ?? '';
            $this->phone     = $organization->phone ?? '';
            $this->timezone  = $organization->timezone ?? '';
            $this->locale    = $organization->locale ?? '';
            $this->slugManuallyEdited = true;
        }
    }

    public function updatedName(string $value): void
    {
        if (! $this->slugManuallyEdited) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug(): void
    {
        $this->slugManuallyEdited = true;
    }

    public function save(): void
    {
        $organizationId = $this->organization?->id;

        $this->validate([
            'name'     => 'required|string|max:255',
            'slug'     => "nullable|string|max:255|unique:organizations,slug,{$organizationId}|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/",
            'email'    => 'nullable|email|max:255',
            'phone'    => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:100',
            'locale'   => 'nullable|string|max:10',
        ]);

        $data = [
            'name'     => $this->name,
            'slug'     => $this->slug ?: null,
            'email'    => $this->email ?: null,
            'phone'    => $this->phone ?: null,
            'timezone' => $this->timezone ?: null,
            'locale'   => $this->locale ?: null,
        ];

        $service = app(OrganizationService::class);

        if ($this->organization && $this->organization->exists) {
            $service->update($this->organization, $data);
        } else {
            $service->create($data, auth()->user());
        }

        $this->redirect(route('core.organizations.index'), navigate: true);
    }

    public function render()
    {
        return view('organizations::livewire.create-organization');
    }
}
