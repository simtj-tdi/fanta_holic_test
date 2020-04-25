<?php



return [
    'icert' => [
        'sitecode' => env('NICE_SITECODE'),
        'sitepasswd' => env('NICE_SITEPASSWD'),
        'path' => env('NICE_PATH'),
        'url' => [
            'success' => env('APP_URL') . '/api/cpclient/success',
            'fail' => env('APP_URL') . '/api/cpclient/fail',
        ]
    ],
];