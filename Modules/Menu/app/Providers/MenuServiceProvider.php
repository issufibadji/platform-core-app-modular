<?php

namespace Modules\Menu\Providers;

use Modules\Menu\Services\MenuService;
use Nwidart\Modules\Support\ModuleServiceProvider;

class MenuServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Menu';
    protected string $nameLower = 'menu';
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->singleton(MenuService::class);
    }
}
