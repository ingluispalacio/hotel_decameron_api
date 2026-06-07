#!/bin/sh
set -e

: "${PORT:=8080}"

php artisan config:cache

MAX_ATTEMPTS=10
ATTEMPT=1
until php artisan migrate --force; do
  if [ "$ATTEMPT" -ge "$MAX_ATTEMPTS" ]; then
    echo "Database not ready after $MAX_ATTEMPTS attempts."
    exit 1
  fi
  echo "Database unavailable, retrying in 5 seconds... (attempt $ATTEMPT/$MAX_ATTEMPTS)"
  ATTEMPT=$((ATTEMPT + 1))
  sleep 5
done

exec php artisan serve --host=0.0.0.0 --port="${PORT}"
