<?php

namespace Modules\Roles\Providers;

use Livewire\Livewire;
use Modules\Roles\Http\Livewire\CreateRole;
use Modules\Roles\Http\Livewire\ListRoles;
use Nwidart\Modules\Support\ModuleServiceProvider;

class RolesServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Roles';
    protected string $nameLower = 'roles';
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        Livewire::component('roles.list-roles', ListRoles::class);
        Livewire::component('roles.create-role', CreateRole::class);
    }
}
