<?php

namespace Modules\SharedUI\Providers;

use Illuminate\Support\Facades\Blade;
use Nwidart\Modules\Support\ModuleServiceProvider;

class SharedUIServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'SharedUI';
    protected string $nameLower = 'sharedui';
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        // Register anonymous components under <x-ui::component-name />
        Blade::anonymousComponentPath(
            module_path('SharedUI', '/resources/views/components'),
            'ui'
        );
    }
}
