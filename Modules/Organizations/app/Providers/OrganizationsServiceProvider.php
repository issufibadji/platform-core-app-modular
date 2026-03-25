<?php

namespace Modules\Organizations\Providers;

use Livewire\Livewire;
use Modules\Organizations\Http\Livewire\CreateOrganization;
use Modules\Organizations\Http\Livewire\ListOrganizations;
use Nwidart\Modules\Support\ModuleServiceProvider;

class OrganizationsServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Organizations';

    protected string $nameLower = 'organizations';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('organizations.list-organizations', ListOrganizations::class);
        Livewire::component('organizations.create-organization', CreateOrganization::class);
    }
}
