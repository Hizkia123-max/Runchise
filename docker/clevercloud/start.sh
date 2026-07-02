#!/bin/sh
set -e

export PORT="${PORT:-8080}"
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

exec supervisord -c /etc/supervisor.d/supervisord.ini
