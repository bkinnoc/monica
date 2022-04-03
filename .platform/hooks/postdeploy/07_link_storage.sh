#!/bin/bash

# Run Caches

# After the deployment, it's highly recommended
# to re-run the caches for config, routes and views.

php artisan optimize

rm -f public/storage

php artisan storage:link
exit 0
