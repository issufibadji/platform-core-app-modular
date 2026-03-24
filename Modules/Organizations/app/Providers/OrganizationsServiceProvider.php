<?php

namespace Modules\Organizations\Providers;

use Livewire\Livewire;
use Modules\Organizations\Http\Livewire\OrganizationCreate;
use Modules\Organizations\Http\Livewire\OrganizationIndex;
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

    private function registerLivewireComponents(): void
    {
        Livewire::component('organizations.organization-index', OrganizationIndex::class);
        Livewire::component('organizations.organization-create', OrganizationCreate::class);
    }
}
