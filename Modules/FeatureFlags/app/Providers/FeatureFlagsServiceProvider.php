<?php

namespace Modules\FeatureFlags\Providers;

use Livewire\Livewire;
use Modules\FeatureFlags\Http\Livewire\CreateFeatureFlag;
use Modules\FeatureFlags\Http\Livewire\ListFeatureFlags;
use Modules\FeatureFlags\Services\FeatureFlagService;
use Nwidart\Modules\Support\ModuleServiceProvider;

class FeatureFlagsServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'FeatureFlags';

    protected string $nameLower = 'featureflags';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->singleton(FeatureFlagService::class);
    }

    public function boot(): void
    {
        parent::boot();

        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('featureflags.list-feature-flags',  ListFeatureFlags::class);
        Livewire::component('featureflags.create-feature-flag', CreateFeatureFlag::class);
    }
}
