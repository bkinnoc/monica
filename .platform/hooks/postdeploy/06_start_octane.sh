#!/bin/bash
# Need to restart octane after re-optimizing the application
supervisorctl restart laravel-octane > storage/logs/octane.log 2>&1
exit 0
