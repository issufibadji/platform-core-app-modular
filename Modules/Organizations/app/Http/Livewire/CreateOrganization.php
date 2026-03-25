<?php

namespace Modules\Organizations\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;

class CreateOrganization extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/')]
    public string $slug = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $phone = '';

    #[Validate('nullable|string|max:100')]
    public string $timezone = '';

    #[Validate('nullable|string|max:10')]
    public string $locale = '';

    public bool $slugManuallyEdited = false;

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

    public function save(OrganizationService $service): void
    {
        $this->validate([
            'name'     => 'required|string|max:255',
            'slug'     => 'nullable|string|max:255|unique:organizations,slug|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'email'    => 'nullable|email|max:255',
            'phone'    => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:100',
            'locale'   => 'nullable|string|max:10',
        ]);

        $organization = $service->create(
            data: [
                'name'     => $this->name,
                'slug'     => $this->slug ?: null,
                'email'    => $this->email ?: null,
                'phone'    => $this->phone ?: null,
                'timezone' => $this->timezone ?: null,
                'locale'   => $this->locale ?: null,
            ],
            owner: auth()->user(),
        );

        $this->redirect(route('core.organizations.index'), navigate: true);
    }

    public function render()
    {
        return view('organizations::livewire.create-organization');
    }
}
