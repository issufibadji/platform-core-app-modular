<?php

return [
    [
        'group'  => 'Platform',
        'items'  => [
            [
                'label'      => 'Dashboard',
                'route'      => 'dashboard',
                'icon'       => 'home',
                'permission' => null,
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
                'label'      => 'Audit Log',
                'route'      => 'core.auditlog.index',
                'icon'       => 'clipboard-document-list',
                'permission' => 'core.auditlog.view',
                'sort'       => 2,
            ],
            [
                'label'      => 'Notifications',
                'route'      => 'core.notifications.index',
                'icon'       => 'bell',
                'permission' => 'core.notifications.view',
                'sort'       => 3,
            ],
            [
                'label'      => 'Files',
                'route'      => 'core.files.index',
                'icon'       => 'paper-clip',
                'permission' => 'core.files.view',
                'sort'       => 4,
            ],
        ],
    ],
];
