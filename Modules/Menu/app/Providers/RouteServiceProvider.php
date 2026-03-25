<?php

namespace Modules\Menu\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Menu';

    public function boot(): void { parent::boot(); }

    public function map(): void {}
}
