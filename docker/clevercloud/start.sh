#!/bin/sh
set -e

export PORT="${PORT:-8080}"
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# PHP-FPM only populates $_SERVER/$_ENV (which CodeIgniter reads) from explicit
# env[] pool directives, not from the worker's inherited process environment.
# Generate them dynamically so every Clever Cloud-injected var is exposed.
{
    echo "[www]"
    env | while IFS='=' read -r key value; do
        escaped=$(printf '%s' "$value" | sed 's/\\/\\\\/g; s/"/\\"/g')
        printf 'env[%s] = "%s"\n' "$key" "$escaped"
    done
} > /usr/local/etc/php-fpm.d/zz-env.conf

exec supervisord -c /etc/supervisor.d/supervisord.ini
