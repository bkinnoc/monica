<?php

return [
    'url' => env('MAILCOW_URL', 'localhost'),
    'domain' => env('MAILCOW_DOMAIN', 'localhost'),
    'host' => env('MAILCOW_HOST', 'localhost'),
    'debug' => env('MAILCOW_DEBUG', false),
    'quota' => env('MAILCOW_QUOTA', 1024),
    'api_key' => env('MAILCOW_API_KEY'),

];