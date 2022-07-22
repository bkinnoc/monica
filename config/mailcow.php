<?php

return [
    'url' => env('MAILCOW_WEB', 'http://localhost:8480'),
    'domain' => env('MAILCOW_DOMAIN', 'localhost'),
    'host' => env('MAILCOW_HOST', 'http://localhost:8480'),
    'debug' => env('MAILCOW_DEBUG', false),
    'quota' => env('MAILCOW_QUOTA', 1024),
    'api_key' => env('MAILCOW_API_KEY'),

];
