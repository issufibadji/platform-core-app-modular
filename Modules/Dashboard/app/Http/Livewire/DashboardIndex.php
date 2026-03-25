<?php

namespace Modules\Dashboard\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardIndex extends Component
{
    public int $organizationsCount  = 0;
    public int $usersCount          = 0;
    public int $unreadNotifications = 0;
    public int $featureFlagsCount   = 0;
    public int $recentAuditCount    = 0;

    public function mount(): void
    {
        // Organizations count — safe fallback if module absent
        if (class_exists(\Modules\Organizations\Models\Organization::class)) {
            $this->organizationsCount = \Modules\Organizations\Models\Organization::count();
        }

        // Users count
        if (class_exists(\App\Models\User::class)) {
            $this->usersCount = \App\Models\User::count();
        }

        // Unread notifications for current user
        $user = Auth::user();
        if ($user && method_exists($user, 'unreadNotifications')) {
            $this->unreadNotifications = $user->unreadNotifications()->count();
        }

        // Active feature flags count — safe fallback
        if (class_exists(\Modules\FeatureFlags\Models\FeatureFlag::class)) {
            $this->featureFlagsCount = \Modules\FeatureFlags\Models\FeatureFlag::where('is_enabled', true)->count();
        }

        // Recent audit log count — safe fallback
        if (class_exists(\Modules\AuditLog\Models\AuditEntry::class)) {
            $this->recentAuditCount = \Modules\AuditLog\Models\AuditEntry::where(
                'created_at', '>=', now()->subDays(7)
            )->count();
        }
    }

    public function render()
    {
        return view('dashboard::livewire.dashboard-index');
    }
}
