<?php

return [

    'default' => 'sales',

    'groups' => [
        'superadmin' => [
            'title' => 'Super Admin',
            'description' => 'Full access to all system features and configurations.',
        ],

        'owner' => [
            'title' => 'Owner',
            'description' => 'Company owner with full business overview access.',
        ],

        'general_manager' => [
            'title' => 'General Manager',
            'description' => 'Manages overall company operations and departments.',
        ],

        'sales_manager' => [
            'title' => 'Sales Manager',
            'description' => 'Oversees sales team performance and targets.',
        ],

        'finance_manager' => [
            'title' => 'Finance Manager',
            'description' => 'Manages financial reports, budgeting, and cash flow.',
        ],

        'supervisor' => [
            'title' => 'Supervisor',
            'description' => 'Supervises daily operational activities and staff.',
        ],

        'sales' => [
            'title' => 'Sales',
            'description' => 'Handles customer sales and product offering.',
        ],

        'admin' => [
            'title' => 'Admin',
            'description' => 'Handles system administration and data management.',
        ],

        'kasir' => [
            'title' => 'Kasir',
            'description' => 'Handles payment transactions and cashier operations.',
        ],

        'pdi' => [
            'title' => 'PDI',
            'description' => 'Pre-Delivery Inspection and product checking before delivery.',
        ],

        'driver' => [
            'title' => 'Driver',
            'description' => 'Responsible for product delivery and logistics transport.',
        ],

        'marketing' => [
            'title' => 'Marketing',
            'description' => 'Handles promotion, advertising, and market strategy.',
        ],

        'support_maintenance' => [
            'title' => 'Support & Maintenance',
            'description' => 'Handles technical support and system maintenance.',
        ],
    ],
];