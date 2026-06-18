#!/usr/bin/env sh
set -eu

docker compose exec -T app php artisan db:seed
