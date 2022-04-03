#!/bin/bash
# Stop octane servers otherwise we end up with 503 errors
service nginx stop
php artisan octane:stop
fuser -k 8000/tcp
exit 0
