<?php

return [
    [
        'group'  => 'Platform',
        'items'  => [
            [
                'label'      => 'Dashboard',
                'route'      => 'core.dashboard.index',
                'icon'       => 'home',
                'permission' => 'core.dashboard.view',
                'sort'       => 1,
            ],
        ],
    ],
    [
        'group' => 'Core',
        'items' => [
            [
                'label'      => 'Organizations',
                'route'      => 'core.organizations.index',
                'icon'       => 'building-office-2',
                'permission' => 'core.organizations.view',
                'sort'       => 1,
            ],
            [
                'label'      => 'Users',
                'route'      => 'core.users.index',
                'icon'       => 'users',
                'permission' => 'core.users.view',
                'sort'       => 2,
            ],
        ],
    ],
    [
        'group' => 'Access Control',
        'items' => [
            [
                'label'      => 'Roles',
                'route'      => 'core.roles.index',
                'icon'       => 'shield-check',
                'permission' => 'core.roles.view',
                'sort'       => 1,
            ],
            [
                'label'      => 'Permissions',
                'route'      => 'core.permissions.index',
                'icon'       => 'key',
                'permission' => 'core.permissions.view',
                'sort'       => 2,
            ],
        ],
    ],
    [
        'group' => 'System',
        'items' => [
            [
                'label'      => 'Settings',
                'route'      => 'core.settings.index',
                'icon'       => 'cog-6-tooth',
                'permission' => 'core.settings.view',
                'sort'       => 1,
            ],
            [
                'label'      => 'Feature Flags',
                'route'      => 'core.featureflags.index',
                'icon'       => 'bolt',
                'permission' => 'core.featureflags.view',
                'sort'       => 2,
            ],
            [
                'label'      => 'Audit Log',
                'route'      => 'core.auditlog.index',
                'icon'       => 'clipboard-document-list',
                'permission' => 'core.auditlog.view',
                'sort'       => 3,
            ],
            [
                'label'      => 'Notifications',
                'route'      => 'core.notifications.index',
                'icon'       => 'bell',
                'permission' => 'core.notifications.view',
                'sort'       => 4,
            ],
            [
                'label'      => 'Files',
                'route'      => 'core.files.index',
                'icon'       => 'paper-clip',
                'permission' => 'core.files.view',
                'sort'       => 5,
            ],
        ],
    ],
];
