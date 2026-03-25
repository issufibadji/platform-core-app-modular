<?php

namespace Modules\Settings\Services;

use Modules\Settings\Models\Setting;

class SettingService
{
    /**
     * Resolve a setting value.
     * Org-specific overrides global; returns $default if neither exists.
     */
    public function get(string $key, mixed $default = null, ?int $organizationId = null): mixed
    {
        // 1. Org-specific value
        if ($organizationId) {
            $setting = Setting::query()
                ->where('key', $key)
                ->where('organization_id', $organizationId)
                ->first();

            if ($setting !== null) {
                return $setting->typedValue();
            }
        }

        // 2. Global value
        $global = Setting::query()
            ->where('key', $key)
            ->whereNull('organization_id')
            ->first();

        return $global !== null ? $global->typedValue() : $default;
    }

    /**
     * Persist (create or update) a setting value.
     */
    public function set(
        string $key,
        mixed $value,
        string $type = 'string',
        ?int $organizationId = null,
        ?string $module = null,
        ?string $group = null,
        bool $isPublic = false,
    ): Setting {
        $serialized = is_array($value) ? json_encode($value) : (string) $value;

        return Setting::updateOrCreate(
            [
                'key'             => $key,
                'organization_id' => $organizationId,
            ],
            [
                'value'     => $serialized,
                'type'      => $type,
                'module'    => $module,
                'group'     => $group,
                'is_public' => $isPublic,
            ]
        );
    }

    /**
     * Check whether a setting exists.
     */
    public function has(string $key, ?int $organizationId = null): bool
    {
        return Setting::query()
            ->where('key', $key)
            ->where(function ($q) use ($organizationId) {
                if ($organizationId) {
                    $q->where('organization_id', $organizationId)
                      ->orWhereNull('organization_id');
                } else {
                    $q->whereNull('organization_id');
                }
            })
            ->exists();
    }

    /**
     * Delete a setting.
     */
    public function forget(string $key, ?int $organizationId = null): void
    {
        Setting::query()
            ->where('key', $key)
            ->where('organization_id', $organizationId)
            ->delete();
    }

    /**
     * All settings, optionally filtered by module and/or group.
     */
    public function all(?string $module = null, ?string $group = null): \Illuminate\Support\Collection
    {
        return Setting::query()
            ->whereNull('organization_id')
            ->when($module, fn ($q) => $q->where('module', $module))
            ->when($group,  fn ($q) => $q->where('group',  $group))
            ->orderBy('group')
            ->orderBy('key')
            ->get();
    }
}
