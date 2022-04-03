#!/bin/bash

set -e
set -x

echo -e "Installing packages"
php composer.phar install
php composer.phar update
echo "Migrating databases..."
php artisan migrate
echo "Seeding database..."
# php artisan passport:install
php artisan db:seed
php artisan storage:link
pgp artisan key:generate
# php artisan passport:install
if [ -f 'scripts/ensure_scheduled_commands.sh' ]; then
    bash scripts/ensure_scheduled_commands.sh -n
fi
if [ -f './ensure_scheduled_commands.sh' ]; then
    bash ./ensure_scheduled_commands.sh -n
fi
echo "Done!"
