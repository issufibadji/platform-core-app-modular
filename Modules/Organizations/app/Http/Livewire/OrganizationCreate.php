<?php

namespace Modules\Organizations\Http\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Modules\Organizations\Enums\OrganizationStatus;
use Modules\Organizations\Services\OrganizationService;

#[Layout('layouts.app')]
#[Title('Create Organization')]
class OrganizationCreate extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255|alpha_dash')]
    public string $slug = '';

    #[Validate('required|string')]
    public string $status = 'active';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $phone = '';

    #[Validate('nullable|string|max:100')]
    public string $timezone = '';

    #[Validate('nullable|string|max:10')]
    public string $locale = '';

    public function save(OrganizationService $service): void
    {
        $this->validate();

        $service->create([
            'name'     => $this->name,
            'slug'     => $this->slug ?: null,
            'status'   => $this->status,
            'email'    => $this->email ?: null,
            'phone'    => $this->phone ?: null,
            'timezone' => $this->timezone ?: null,
            'locale'   => $this->locale ?: null,
        ]);

        session()->flash('success', 'Organization created successfully.');

        $this->redirect(route('core.organizations.index'), navigate: true);
    }

    public function render()
    {
        return view('organizations::livewire.organization-create', [
            'statuses' => OrganizationStatus::cases(),
        ]);
    }
}
