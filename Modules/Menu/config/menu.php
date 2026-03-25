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
];
