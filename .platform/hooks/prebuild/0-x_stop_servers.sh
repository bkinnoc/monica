#!/bin/sh

# Restart web services
mkdir -p /var/log/php-fpm
touch /var/log/php-fpm/error.log
if [ -f "/var/run/php-fpm/www.sock" ]; then
    service php-fpm stop
fi
if [ -f "/var/run/nginx.pid" ]; then
    service nginx stop
fi
if [ -f "/var/run/nginx.pid" ]; then
    supervisorctl stop all
fi
exit 0
