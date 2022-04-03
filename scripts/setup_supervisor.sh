#!/bin/bash

set -e
set -x

#
# Author: Günter Grodotzki (gunter@grodotzki.co.za)
# Version: 2015-04-25
#
# install supervisord
#
# See:
# - https://github.com/Supervisor/initscripts
# - http://supervisord.org/

if [[ "$SUPERVISE" -eq "enable" ]]
then

  export HOME="/root"
  export PATH="/sbin:/bin:/usr/sbin:/usr/bin:/opt/aws/bin"

if [ ! -f /etc/supervisord.conf ]; then
  cat <<'EOB' > /etc/supervisord.conf
; supervisor config file

[unix_http_server]
file=/var/run/supervisor/supervisor.sock   ; (the path to the socket file)
chmod=0700                       ; sockef file mode (default 0700)

[supervisord]
logfile=$APP_DIR/storage/logs/supervisor/supervisord.log ; (main log file;default $CWD/supervisord.log)
pidfile=/var/run/supervisord.pid ; (supervisord pidfile;default supervisord.pid)
childlogdir=$APP_DIR/storage/logs/supervisor            ; ('AUTO' child log dir, default $TEMP)

; the below section must remain in the config file for RPC
; (supervisorctl/web interface) to work, additional interfaces may be
; added by defining them in separate rpcinterface: sections
[rpcinterface:supervisor]
supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface

[supervisorctl]
serverurl=unix:///var/run/supervisor/supervisor.sock ; use a unix:// URL  for a unix socket

; The [include] section can just contain the "files" setting.  This
; setting can list multiple files (separated by whitespace or
; newlines).  It can also contain wildcards.  The filenames are
; interpreted as relative to this file.  Included files *cannot*
; include files themselves.

[include]
files = /etc/supervisord.d/*.ini
; Change according to your configurations
EOB
fi

mkdir -p /etc/supervisord.d/
CORES=$(nproc)
CORES_FOR_DEFAULT=$((CORES*2))
OCTANE_MAX_REQUESTS="${OCTANE_MAX_REQUESTS:-1000}"
APP_DIR="${APP_DIR:-/var/www/html}"
if [ -d /var/app/current ]; then
  APP_DIR="/var/app/current"
fi

mkdir -p $APP_DIR/storage/logs

# Run queues only on the worker server
if [[ "$EB_ROLE" -eq 'worker'  ||  "$EB_ROLE" -eq 'combined' ]]; then
    touch /etc/supervisord.d/laravel-worker.ini
    touch /etc/supervisord.d/laravel-worker-high.ini
    touch $APP_DIR/storage/logs/laravel-worker.log
    touch $APP_DIR/storage/logs/laravel-worker-high.log
    # Setup the default priority queue
    QUEUE_ARGS="--sleep=10 --tries=5 --timeout=120 --queue=default --memory=64 --stop-when-empty"
    cat <<EOB > /etc/supervisord.d/laravel-worker.ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_DIR/artisan queue:work $QUEUE_ARGS
autostart=true
autorestart=true
user=root
numprocs=$CORES_FOR_DEFAULT
startsecs=0
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/laravel-worker.log
EOB

    # Setup the high priority queue
    QUEUE_ARGS="--sleep=10 --tries=2 --timeout=1810 --queue=high --memory=128 --stop-when-empty"
    cat <<EOB > /etc/supervisord.d/laravel-worker-high.ini
[program:laravel-worker-high]
process_name=%(program_name)s_%(process_num)02d
command=php $APP_DIR/artisan queue:work $QUEUE_ARGS
autostart=true
autorestart=true
user=root
numprocs=$CORES
startsecs=0
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/laravel-worker-high.log
EOB
fi

if [[ "$EB_ROLE" -eq 'api'  ||  "$EB_ROLE" -eq 'combined' ]]; then
# Run horizon only on the api servers
    touch /etc/supervisord.d/laravel-horizon.ini
    touch $APP_DIR/storage/logs/laravel-horizon.log
    cat <<EOB > /etc/supervisord.d/laravel-horizon.ini
[program:laravel-horizon]
process_name=%(program_name)s
command=php $APP_DIR/artisan horizon
autostart=true
autorestart=true
user=root
redirect_stderr=true
stdout_logfile=$APP_DIR/storage/logs/laravel-horizon.log
EOB

touch /etc/supervisord.d/laravel-octane.ini
touch $APP_DIR/storage/logs/octane.log
cat <<EOB > /etc/supervisord.d/laravel-octane.ini
[program:laravel-octane]
command=php $APP_DIR/artisan octane:start --host=0.0.0.0 --max-requests=$OCTANE_MAX_REQUESTS --port=8000 --server=swoole
user=root
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stdout_logfile=$APP_DIR/storage/logs/octane.log
stderr_logfile_maxbytes=0
EOB
fi
  mkdir -p $APP_DIR/storage/logs/supervisor
  mkdir -p /var/run/supervisor
  touch $APP_DIR/storage/logs/supervisor/supervisord.log
  if [[ ! -f '/var/run/supervisord.pid' ]]
  then
    supervisord -c /etc/supervisord.conf
  fi
  supervisorctl reread
  supervisorctl update
  if [[ "$EB_ROLE" -eq 'worker'  ||  "$EB_ROLE" -eq 'combined' ]]
  then
    supervisorctl start laravel-worker:*
    supervisorctl restart laravel-worker-high:*
  else
    supervisorctl start laravel-horizon:*
  fi
  supervisorctl status
fi
exit 0
