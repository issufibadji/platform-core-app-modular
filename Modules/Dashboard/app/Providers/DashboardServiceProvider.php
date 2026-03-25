<?php

namespace Modules\Dashboard\Providers;

use Livewire\Livewire;
use Modules\Dashboard\Http\Livewire\DashboardIndex;
use Nwidart\Modules\Support\ModuleServiceProvider;

class DashboardServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Dashboard';

    protected string $nameLower = 'dashboard';

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
        Livewire::component('dashboard.dashboard-index', DashboardIndex::class);
    }
}
