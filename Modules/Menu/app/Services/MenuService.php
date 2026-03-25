<?php

namespace Modules\Menu\Services;

class MenuService
{
    /**
     * Return menu groups visible to the current user.
     * Falls back to showing all items when the permission system is not yet configured.
     */
    public function forUser(): array
    {
        $groups = config('menu', []);

        return array_values(
            array_filter(
                array_map(function (array $group) {
                    $group['items'] = array_values(
                        array_filter($group['items'], fn ($item) => $this->canSee($item))
                    );
                    return $group;
                }, $groups),
                fn ($group) => ! empty($group['items'])
            )
        );
    }

    protected function canSee(array $item): bool
    {
        if (! auth()->check()) {
            return false;
        }

        if ($item['permission'] === null) {
            return true;
        }

        try {
            return auth()->user()->can($item['permission']);
        } catch (\Throwable) {
            // Permissions not configured yet — show all items
            return true;
        }
    }
}
