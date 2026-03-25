<?php

namespace Modules\Notifications\Providers;

use Livewire\Livewire;
use Modules\Notifications\Http\Livewire\ListNotifications;
use Nwidart\Modules\Support\ModuleServiceProvider;

class NotificationsServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Notifications';

    protected string $nameLower = 'notifications';

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
        Livewire::component('notifications.list-notifications', ListNotifications::class);
    }
}
