<?php

namespace Modules\Settings\Providers;

use Livewire\Livewire;
use Modules\Settings\Http\Livewire\EditSetting;
use Modules\Settings\Http\Livewire\ListSettings;
use Nwidart\Modules\Support\ModuleServiceProvider;

class SettingsServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Settings';

    protected string $nameLower = 'settings';

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
        Livewire::component('settings.list-settings', ListSettings::class);
        Livewire::component('settings.edit-setting',  EditSetting::class);
    }
}
