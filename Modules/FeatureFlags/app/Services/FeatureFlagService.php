<?php

namespace Modules\FeatureFlags\Services;

use Modules\FeatureFlags\Models\FeatureFlag;

class FeatureFlagService
{
    /**
     * Check if a feature flag is enabled.
     * Organization-specific flag takes precedence over global.
     */
    public function isEnabled(string $key, ?int $organizationId = null): bool
    {
        // 1. Check org-specific override
        if ($organizationId) {
            $flag = FeatureFlag::where('key', $key)
                ->where('organization_id', $organizationId)
                ->first();

            if ($flag !== null) {
                return (bool) $flag->is_enabled;
            }
        }

        // 2. Fall back to global flag
        $global = FeatureFlag::where('key', $key)
            ->whereNull('organization_id')
            ->first();

        return $global ? (bool) $global->is_enabled : false;
    }

    /**
     * Enable a flag (global or org-specific).
     */
    public function enable(string $key, ?int $organizationId = null): FeatureFlag
    {
        return $this->set($key, true, $organizationId);
    }

    /**
     * Disable a flag (global or org-specific).
     */
    public function disable(string $key, ?int $organizationId = null): FeatureFlag
    {
        return $this->set($key, false, $organizationId);
    }

    /**
     * Create or update a flag.
     */
    public function set(string $key, bool $enabled, ?int $organizationId = null, ?string $description = null, ?string $module = null): FeatureFlag
    {
        $flag = FeatureFlag::firstOrNew([
            'key'             => $key,
            'organization_id' => $organizationId,
        ]);

        $flag->is_enabled = $enabled;

        if ($description !== null) {
            $flag->description = $description;
        }

        if ($module !== null) {
            $flag->module = $module;
        }

        $flag->save();

        return $flag;
    }

    /**
     * Check if a flag record exists.
     */
    public function has(string $key, ?int $organizationId = null): bool
    {
        return FeatureFlag::where('key', $key)
            ->where('organization_id', $organizationId)
            ->exists();
    }

    /**
     * Delete a flag.
     */
    public function forget(string $key, ?int $organizationId = null): void
    {
        FeatureFlag::where('key', $key)
            ->where('organization_id', $organizationId)
            ->delete();
    }
}
