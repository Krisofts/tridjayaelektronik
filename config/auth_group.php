<?php

return [

    'default' => 'user',

    'groups' => [
        'superadmin' => [
            'title' => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],

        'admin' => [
            'title' => 'Admin',
            'description' => 'Day to day administrators of the site.',
        ],

        'developer' => [
            'title' => 'Developer',
            'description' => 'Site programmers.',
        ],

        'user' => [
            'title' => 'User',
            'description' => 'General users of the site.',
        ],

        'beta' => [
            'title' => 'Beta User',
            'description' => 'Has access to beta features.',
        ],
    ],
];