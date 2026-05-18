<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions List
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'admin.access'        => 'Access admin area',
        'admin.settings'      => 'Access settings',
        'users.manage'        => 'Manage users',
        'users.create'        => 'Create users',
        'users.edit'          => 'Edit users',
        'users.delete'        => 'Delete users',
        'beta.access'         => 'Access beta features',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Permission Matrix
    |--------------------------------------------------------------------------
    */
    'matrix' => [
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
        ],

        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],

        'developer' => [
            'admin.access',
            'admin.settings',
            'users.create',
            'users.edit',
            'beta.access',
        ],

        'user' => [],

        'beta' => [
            'beta.access',
        ],
    ],
];