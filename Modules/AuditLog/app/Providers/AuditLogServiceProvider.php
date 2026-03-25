<?php

namespace Modules\AuditLog\Providers;

use Livewire\Livewire;
use Modules\AuditLog\Http\Livewire\ListAuditLogs;
use Nwidart\Modules\Support\ModuleServiceProvider;

class AuditLogServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'AuditLog';

    protected string $nameLower = 'auditlog';

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
        Livewire::component('auditlog.list-audit-logs', ListAuditLogs::class);
    }
}
