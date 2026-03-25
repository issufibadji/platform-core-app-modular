<?php

namespace Modules\Permissions\Providers;

use Livewire\Livewire;
use Modules\Permissions\Http\Livewire\ListPermissions;
use Nwidart\Modules\Support\ModuleServiceProvider;

class PermissionsServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Permissions';
    protected string $nameLower = 'permissions';
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        Livewire::component('permissions.list-permissions', ListPermissions::class);
    }
}
