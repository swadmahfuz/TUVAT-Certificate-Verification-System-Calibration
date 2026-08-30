<?php

return [
    'app_key' => env('CVS_APP_KEY', 'calibration'),

    'registration_enabled' => env('CVS_REGISTRATION_ENABLED', false),

    'apps' => [
        'training' => 'Training CVS',
        'inspection' => 'Inspection CVS',
        'calibration' => 'Calibration CVS',
        'reports' => 'Reports CVS',
        'certification' => 'BA Certification',
    ],

    'access_levels' => [
        'view' => 'View only',
        'full' => 'Full access',
    ],

    'shared_activity_subject_types' => ['auth', 'user', 'department'],

    'cache_ttl' => [
        'dashboard' => (int) env('CVS_DASHBOARD_CACHE_TTL', 300),
        'permissions' => (int) env('CVS_PERMISSIONS_CACHE_TTL', 900),
    ],

    'certificate_search' => [
        'like' => [
            'certificate_number',
            'calibrator',
            'client_name',
            'location',
            'equipment_name',
            'equipment_brand',
            'equipment_id',
        ],
        'exact' => [],
        'date_like' => [
            'calibration_date',
            'report_issue_date',
            'validity_date',
        ],
    ],
];
