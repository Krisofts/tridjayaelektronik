<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions List
    |--------------------------------------------------------------------------
    | Format: module.action
    */

    'permissions' => [

        // Admin & System
        'admin.access'        => 'Access admin area',
        'admin.settings'      => 'Access system settings',

        // User Management
        'users.manage'        => 'Manage users',
        'users.create'        => 'Create users',
        'users.edit'          => 'Edit users',
        'users.delete'        => 'Delete users',

        // Sales
        'sales.access'        => 'Access sales module',
        'sales.create'        => 'Create sales transaction',
        'sales.edit'          => 'Edit sales transaction',
        'sales.delete'        => 'Delete sales transaction',

        // Finance
        'finance.access'      => 'Access finance module',
        'finance.report'      => 'View financial reports',
        'finance.manage'      => 'Manage finance data',

        // Inventory / Product
        'inventory.access'    => 'Access inventory module',
        'inventory.manage'    => 'Manage inventory stock',

        // Operations
        'operations.access'   => 'Access operations module',
        'delivery.manage'     => 'Manage delivery & logistics',

        // Marketing
        'marketing.access'    => 'Access marketing module',
        'marketing.manage'    => 'Manage marketing campaigns',

        // Support & Maintenance
        'support.access'      => 'Access support module',
        'support.manage'      => 'Manage support & maintenance',

        // Beta / Experimental
        'beta.access'         => 'Access beta features',
    ],


    /*
    |--------------------------------------------------------------------------
    | Role Permission Matrix
    |--------------------------------------------------------------------------
    */

    'matrix' => [

        'superadmin' => [
            '*', // full access semua module
        ],

        'owner' => [
            'admin.access',
            'users.manage',
            'sales.*',
            'finance.*',
            'inventory.*',
            'operations.*',
            'marketing.*',
            'support.*',
        ],

        'general_manager' => [
            'admin.access',
            'users.manage',
            'sales.*',
            'finance.report',
            'inventory.access',
            'operations.access',
            'marketing.access',
            'support.access',
        ],

        'sales_manager' => [
            'sales.*',
            'inventory.access',
            'marketing.access',
        ],

        'finance_manager' => [
            'finance.*',
            'inventory.access',
        ],

        'supervisor' => [
            'sales.access',
            'inventory.access',
            'operations.access',
        ],

        'sales' => [
            'sales.create',
            'sales.access',
        ],

        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'inventory.manage',
        ],

        'kasir' => [
            'sales.create',
            'sales.access',
        ],

        'pdi' => [
            'inventory.access',
            'operations.access',
        ],

        'driver' => [
            'operations.access',
        ],

        'marketing' => [
            'marketing.access',
            'marketing.manage',
        ],

        'support_maintenance' => [
            'support.access',
            'support.manage',
        ],

    ],
];