<?php

namespace Modules\Users\Providers;

use Livewire\Livewire;
use Modules\Users\Http\Livewire\CreateUser;
use Modules\Users\Http\Livewire\ListUsers;
use Nwidart\Modules\Support\ModuleServiceProvider;

class UsersServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Users';

    protected string $nameLower = 'users';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        Livewire::component('users.list-users', ListUsers::class);
        Livewire::component('users.create-user', CreateUser::class);
    }
}
