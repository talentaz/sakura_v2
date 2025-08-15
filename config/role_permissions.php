<?php

return [
    'admin' => [
        'default_url' => 'admin.dashboard.index',
        'allowed_routes' => [
            'root',
            'admin.dashboard.*',
            'admin.port.*',
            'admin.vehicle.*',
            'admin.bodyType.*',
            'admin.makerType.*',
            'admin.customer.*',
            'admin.news.*',
            'admin.video.*',
            'admin.page_setting.*',
            'admin.user.*',
            'admin.customer_management.*',
            'admin.inquiry.*',
            'admin.invoice.*',
            'admin.shipping.*',
            'admin.notification.*',
            'admin.edit_profile',
            'admin.update_profile',
            'admin.reports.*',
            'logout',
        ],
        'menu_items' => [
            'dashboard',
            'port',
            'rate',
            'vehicle_management',
            'category',
            'customer_voice',
            'news',
            'video',
            'page_management',
            'user_management',
            'customer_management',
            'inquiry',
            'invoice',
            'shipping',
            'reports',
        ]
    ],
    
    'sales_manager' => [
        'default_url' => 'admin.inquiry.index',
        'allowed_routes' => [
            'admin.inquiry.*',
            'admin.invoice.*',
            'admin.edit_profile',
            'admin.update_profile',
            'admin.reports.*',
            'logout',
        ],
        'menu_items' => [
            'inquiry',
            'invoice',
            'reports',
        ]
    ],
    
    'sales_agent' => [
        'default_url' => 'admin.inquiry.index',
        'allowed_routes' => [
            'admin.inquiry.*',
            'admin.invoice.*',
            'admin.edit_profile',
            'admin.update_profile',
            'admin.reports.*',
            'logout',
        ],
        'menu_items' => [
            'inquiry',
            'invoice',
            'reports',
        ]
    ],
    
    'shipment_officer' => [
        'default_url' => 'admin.shipping.index',
        'allowed_routes' => [
            'admin.shipping.*',
            'admin.inquiry.index',
            'admin.inquiry.show',
            'admin.invoice.index',
            'admin.invoice.show',
            'admin.edit_profile',
            'admin.update_profile',
            'admin.reports.*',
            'logout',
        ],
        'menu_items' => [
            'shipping',
            'inquiry_view',
            'invoice_view',
            'reports',
        ]
    ],
    
    'purchaser' => [
        'default_url' => 'admin.vehicle.index',
        'allowed_routes' => [
            'admin.vehicle.*',
            'admin.bodyType.*',
            'admin.makerType.*',
            'admin.port.*',
            'admin.edit_profile',
            'admin.update_profile',
            'admin.reports.*',
            'logout',
        ],
        'menu_items' => [
            'vehicle_management',
            'category',
            'port',
            'reports',
        ]
    ],
];
