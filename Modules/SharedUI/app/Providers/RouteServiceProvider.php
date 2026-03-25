<?php

namespace Modules\SharedUI\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'SharedUI';

    public function boot(): void { parent::boot(); }

    public function map(): void {}
}
