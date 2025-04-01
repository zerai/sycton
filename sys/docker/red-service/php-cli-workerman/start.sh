#!/usr/bin/env bash
set -e

if [[ ! -d "/var/log/supervisor" ]]; then
    mkdir -p /var/log/supervisor
fi

if [[ "prod" == "${APP_ENV}" ]]; then
    cp "${PHP_INI_DIR}/php.ini-production" "${PHP_INI_DIR}/php.ini"
#    cp "/etc/opcache.ini" "${PHP_INI_DIR}/conf.d/opcache.ini"
else
    cp "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini"
fi

/usr/bin/supervisord -n -c /etc/supervisord.conf

