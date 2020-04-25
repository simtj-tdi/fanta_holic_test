<?php

return [

    'analysis' => [
        'host' => env('ES_TG9_ANALYSIS_HOST', '127.0.0.1'),
        'port' => env('ES_TG9_ANALYSIS_PORT', '9200'),
        'schema' => env('ES_TG9_ANALYSIS_SCHEMA', 'http'),
        'username' => env('ES_TG9_ANALYSIS_USERNAME', ''),
        'password' => env('ES_TG9_ANALYSIS_PASWORD', ''),
        'index' => '',
    ],

    'app' => [
        'host' => env('ES_TG9_APP_HOST', '127.0.0.1'),
        'port' => env('ES_TG9_APP_PORT', '9200'),
        'schema' => env('ES_TG9_APP_SCHEMA', 'http'),
        'username' => env('ES_TG9_APP_USERNAME', ''),
        'password' => env('ES_TG9_APP_PASSWORD', ''),
        'index' => 'logs_tg9_app',
    ],

    'location' => [
        'host' => env('ES_TG9_LOCATION_HOST', '127.0.0.1'),
        'port' => env('ES_TG9_LOCATION_PORT', '9200'),
        'schema' => env('ES_TG9_LOCATION_SCHEMA', 'http'),
        'username' => env('ES_TG9_LOCATION_USERNAME', ''),
        'password' => env('ES_TG9_LOCATION_PASSWORD', ''),
        'index' => 'logs_tg9_location',
    ],
    
    'wifi' => [
        'host' => env('ES_TG9_WIFI_HOST', '127.0.0.1'),
        'port' => env('ES_TG9_WIFI_PORT', '9200'),
        'schema' => env('ES_TG9_WIFI_SCHEMA', 'http'),
        'username' => env('ES_TG9_WIFI_USERNAME', ''),
        'password' => env('ES_TG9_WIFI_PASSWORD', ''),
        'index' => 'logs_tg9_wifi',
    ],

    'bluetooth' => [
        'host' => env('ES_TG9_BLUETOOTH_HOST', '127.0.0.1'),
        'port' => env('ES_TG9_BLUETOOTH_PORT', '9200'),
        'schema' => env('ES_TG9_BLUETOOTH_SCHEMA', 'http'),
        'username' => env('ES_TG9_BLUETOOTH_USERNAME', ''),
        'password' => env('ES_TG9_BLUETOOTH_PASSWORD', ''),
        'index' => 'logs_tg9_bluetooth',
    ],

    'external' => [
        'host' => env('ES_TG9_EXTERNAL_HOST', '127.0.0.1'),
        'port' => env('ES_TG9_EXTERNAL_PORT', '9200'),
        'schema' => env('ES_TG9_EXTERNAL_SCHEMA', 'http'),
        'username' => env('ES_TG9_EXTERNAL_USERNAME', ''),
        'password' => env('ES_TG9_EXTERNAL_PASSWORD', ''),
        'index' => '',
    ],
];
